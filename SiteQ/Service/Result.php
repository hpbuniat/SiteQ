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
 * Result
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011-2012 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/SiteQ
 */
class SiteQ_Service_Result {

    /**
     * The Score
     *
     * @var int
     */
    public $score = 0;

    /**
     * The Service-Name
     *
     * @var string
     */
    public $name = '';

    /**
     * The tests url
     *
     * @var string
     */
    public $url = '';

    /**
     * URL for Web-Access
     *
     * @var string
     */
    public $check = '';

    /**
     * The Suggestions by severity
     *
     * @var array
     */
    public $suggestions = array(
        'low' => array(),
        'medium' => array(),
        'high' => array()
    );

    /**
     * Add a suggestion
     *
     * @param  string $sTitle
     * @param  string $sText
     * @param  array $aDetails
     * @param  string $sSeverity
     *
     * @return SiteQ_Service_Result
     */
    public function add($sTitle, $sText = '', $aDetails = array(), $sSeverity = 'medium') {
        if (isset($this->suggestions[$sSeverity]) === true) {
            $this->suggestions[$sSeverity][] = array(
                'title' => $sTitle,
                'text' => $sText,
                'details' => $aDetails
            );
        }

        return $this;
    }

    /**
     * Get the Results
     *
     * @return array
     */
    public function get() {
        return $this->suggestions;
    }
}