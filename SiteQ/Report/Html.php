<?php
/**
 * SiteQ
 *
 * Copyright (c) 2011, Hans-Peter Buniat <hpbuniat@googlemail.com>.
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
 * @copyright 2011 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * HTMl-Reporter
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/SiteQ
 */
class SiteQ_Report_Html extends SiteQ_Report_AbstractReport {

    /**
     * The Construct
     *
     * @param  string $filename
     * @param  arrray $arguments
     *
     * @return SiteQ_Report_AbstractReport
     */
    public function __construct($filename, $arguments) {
        parent::__construct($filename, $arguments);

        if (is_dir($filename) !== true) {
            mkdir($filename, 0777, true);
        }
    }

    /**
     * (non-PHPdoc)
     * @see SiteQ/Report/SiteQ_Report_AbstractReport::write()
     */
    public function write($aResults) {
        foreach ($aResults as $oResult) {
            $sContent = $this->_request($oResult->check);
            file_put_contents($this->_filename . '/' . preg_replace('/\W/', '_', $oResult->name) . '.html', $sContent);
        }
    }

    /**
     * Perform a http-request
     *
     * @param  string $sUrl
     *
     * @return string
     */
    protected function _request($sUrl) {
        $rCurl = curl_init();
        curl_setopt($rCurl, CURLOPT_URL, $sUrl);
        curl_setopt($rCurl, CURLOPT_HEADER, 0);
        curl_setopt($rCurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rCurl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:2.0) Gecko/20100101 Firefox/4.0');

        $sResponse = curl_exec($rCurl);
        curl_close($rCurl);

        return $sResponse;
    }
}