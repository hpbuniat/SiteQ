<?php

class SiteQ_Report_Json extends SiteQ_Report_AbstractReport {

    /**
     * (non-PHPdoc)
     * @see SiteQ/Report/SiteQ_Report_AbstractReport::write()
     */
    public function write($aResults) {
        file_put_contents($this->_filename, json_encode($aResults));
    }
}