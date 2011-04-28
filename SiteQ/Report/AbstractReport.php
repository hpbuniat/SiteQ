<?php
abstract class SiteQ_Report_AbstractReport {

    protected $_filename = null;

    protected $_arguments = array();

    abstract public function write($aResults);

    public function __construct($filename, $arguments) {
        $this->_filename = $filename;
        $this->_arguments = $arguments;
    }

}