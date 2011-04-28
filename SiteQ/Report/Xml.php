<?php

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

        print_r($this->_document->saveXML());
        file_put_contents($this->_filename, $this->_document->saveXML());
    }

    protected function _addChild($parent, $name, $value) {
        $element = $this->_document->createElement($name, htmlspecialchars($value));
        $parent->appendChild($element);
    }

    protected function _addAttr($parent, $name, $value) {
        $parent->setAttributeNode(new DOMAttr($name, $value));
    }
}