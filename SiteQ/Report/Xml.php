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
 * XML-Reporter
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/SiteQ
 */
class SiteQ_Report_Xml extends SiteQ_Report_AbstractReport {

    /**
     * XML-Document
     *
     * @var DOMDocument
     */
    protected $_document = null;

    /**
     * XML-Root Node
     *
     * @var DOMElement
     */
    protected $_root = null;

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

        $this->_document = new DOMDocument();
        $this->_root = $this->_document->createElement('siteq');
        $this->_document->appendChild($this->_root);
    }

    /**
     * (non-PHPdoc)
     * @see SiteQ/Report/SiteQ_Report_AbstractReport::write()
     */
    public function write($aResults) {
        foreach ($aResults as $oResult) {
            $service = $this->_document->createElement('service');
            $this->_addAttr($service, 'name', $oResult->name);
            $this->_addAttr($service, 'score', $oResult->score);

            $this->_root->appendChild($service);
            foreach ($oResult->get() as $sSeverity => $aEntries) {
                $oSuggestion = $this->_document->createElement('suggestion');
                foreach ($aEntries as $aEntry) {
                    $this->_addAttr($oSuggestion, 'text', $aEntry['text']);
                    $this->_addAttr($oSuggestion, 'severity', $sSeverity);
                    if (empty($aEntry['details']) !== true) {
                        $oDetails = $this->_document->createElement('details');
                        foreach ($aEntry['details'] as $aDetail) {
                            $this->_addAttr($oDetails, 'text', $aDetail['text']);
                            if (empty($aDetail['info']) !== true) {
                                foreach ($aDetail['info'] as $sInfo) {
                                    $this->_addChild($oDetails, 'info', $sInfo);
                                }
                            }
                        }

                        $oSuggestion->appendChild($oDetails);
                    }
                }

                $service->appendChild($oSuggestion);
            }

            $this->_root->appendChild($service);
        }

        file_put_contents($this->_filename, $this->_document->saveXML());
    }

    /**
     * Helper to add a child-element
     *
     * @param  DOMElement $parent
     * @param  string $name
     * @param  mixed $value
     *
     * @return void
     */
    protected function _addChild($parent, $name, $value) {
        $element = $this->_document->createElement($name, htmlspecialchars($value));
        $parent->appendChild($element);
    }

    /**
     * Helper to add a attribute
     *
     * @param  DOMElement $parent
     * @param  string $name
     * @param  mixed $value
     *
     * @return void
     */
    protected function _addAttr($parent, $name, $value) {
        $parent->setAttributeNode(new DOMAttr($name, $value));
    }
}