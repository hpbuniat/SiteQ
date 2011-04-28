<?php

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