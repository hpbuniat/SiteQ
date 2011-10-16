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
 * Result Collector
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/SiteQ
 */
class SiteQ_Collector {

    /**
     * Passed cli-arguments
     *
     * @var array
     */
    protected $_aArguments = array();

    /**
     * The Services to use
     *
     * @var array <SiteQ_Service_AbstractService>
     */
    protected $_aServices = array();

    /**
     * The Collector results
     *
     * @var array <SiteQ_Service_Result>
     */
    protected $_aResults = array();

    /**
     * Set some properties
     *
     * @param  array $aArguments
     * @param  array $aServices
     *
     * @return SiteQ_Collector
     */
    public function set(array $aArguments, array $aServices) {
        $this->_aArguments = $aArguments;
        $this->_aServices = $aServices;
        return $this;
    }

    /**
     * Run the Collector on all Services
     *
     * @return SiteQ_Collector
     */
    public function run() {
        foreach ($this->_aServices as $oService) {
            $this->_aResults[] = $oService->url($this->_aArguments['url'])->query()->parse()->get();
        }

        if (isset($this->_aArguments['report']) === true and count($this->_aArguments['report']) > 0) {
            foreach ($this->_aArguments['report'] as $type => $filename) {
                SiteQ_Report::write($type, $filename, $this->_aResults, $this->_aArguments);
                SiteQ_TextUI_Output::info('Report (' . $type . ') written to: ' . $filename);
            }
        }

        return $this;
    }
}