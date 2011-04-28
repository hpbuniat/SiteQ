<?php

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

        if (isset($this->_aArguments['report']) && count($this->_aArguments['report']) > 0) {
            foreach ($this->_aArguments['report'] as $type => $filename) {
                SiteQ_Report::write($type, $filename, $this->_aResults, $this->_aArguments);
                SiteQ_TextUI_Output::info('Report (' . $type . ') written to: ' . $filename);
            }
        }

        return $this;
    }
}