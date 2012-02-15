<?php
/**
 * SiteQ
 *
 * Copyright (c) 2011-2012, Hans-Peter Buniat <hpbuniat@googlemail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in
 * the documentation and/or other materials provided with the
 * distribution.
 *
 * * Neither the name of Hans-Peter Buniat nor the names of his
 * contributors may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package SiteQ
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2012 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Pagespeed Service
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2012 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/SiteQ
 */
class SiteQ_Service_Pagespeed extends SiteQ_Service_AbstractService {

    /**
     * The Service-Name
     *
     * @var string
     */
    protected $_sName = 'Google PageSpeed';

    /**
     * Replace-Helper
     *
     * @var array
     */
    protected $_aReplace = array();

    /**
     * (non-PHPdoc)
     * @see SiteQ/Service/SiteQ_Service_AbstractService::query()
     */
    public function query() {
        $sPageSpeed = 'https://developers.google.com/_apps/pagespeed/run_pagespeed?url=' . urlencode($this->_sUrl) . '&format=json';
        $rCurl = curl_init();
        curl_setopt($rCurl, CURLOPT_URL, $sPageSpeed);
        curl_setopt($rCurl, CURLOPT_HEADER, 0);
        curl_setopt($rCurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rCurl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:2.0) Gecko/20100101 Firefox/4.0');

        $sResponse = curl_exec($rCurl);
        if ((int) curl_getinfo($rCurl, CURLINFO_HTTP_CODE) == 200) {
            $this->_mRaw = $sResponse;
            SiteQ_TextUI_Output::info($this->_sName . ' took ' . curl_getinfo($rCurl, CURLINFO_TOTAL_TIME) . ' seconds');
        }
        else {
            SiteQ_TextUI_Output::info($this->_sName . ' request failed!');
        }

        curl_close($rCurl);
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see SiteQ/Service/SiteQ_Service_AbstractService::parse()
     */
    public function parse() {
        $aJson = json_decode($this->_mRaw, true);

        $this->_oResult->score = $aJson['results']['score'];
        $this->_oResult->name = $this->_sName;
        $this->_oResult->url = $this->_sUrl;
        $this->_oResult->check = 'http://pagespeed.googlelabs.com/#url=' . urlencode($this->_sUrl);

        foreach ($aJson['results']['rule_results'] as $aResult) {
            if ($aResult['rule_score'] != 100) {
                $this->_oResult->add($aResult['rule_name'], $aResult['localized_rule_name'], $this->_blockHelper($aResult['url_blocks']), $this->_severityHelper($aResult));
            }
        }

        unset($aJson);

        return $this;
    }

    /**
     * Resolve a block
     *
     * @param  array $aDetails
     *
     * @return array
     */
    protected function _blockHelper(array $aDetails) {
        $aReturn = array();
        foreach ($aDetails as $aDetail) {
            $this->_aReplace = $aDetail['header']['args'];
            $aAdd = array(
                'text' => preg_replace_callback('/\$(\d+)/', array(
                    $this,
                    '_replaceHelper'
                ), $aDetail['header']['format']),
                'info' => array()
            );
            foreach ($aDetail['urls'] as $aUrl) {
                $this->_aReplace = $aUrl['result']['args'];
                $aAdd['info'][] = preg_replace_callback('/\$(\d+)/', array(
                    $this,
                    '_replaceHelper'
                ), $aUrl['result']['format']);
            }

            $aReturn[] = $aAdd;
        }

        return $aReturn;
    }

    /**
     * Help with replacing placeholders
     *
     * @param  array $aDetails
     *
     * @return string
     */
    protected function _replaceHelper(array $aMatches) {
        if (isset($aMatches[1]) === true) {
            $iKey = (int) $aMatches[1] - 1;
            return $this->_aReplace[$iKey]['localized_value'];
        }

        return '';
    }

    /**
     * Determine the serverity
     *
     * @param  array $aDetails
     *
     * @return string
     */
    protected function _severityHelper(array $aResult) {
        if ((float) $aResult['rule_impact'] > 10.0) {
            return 'high';
        }
        elseif ((float) $aResult['rule_impact'] > 5.0) {
            return 'medium';
        }

        return 'low';
    }
}
