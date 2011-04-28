<?php
abstract class SiteQ_Service_AbstractService {

    /**
     *
     * @var array
     */
    protected $_mRaw;

    /**
     * The Url
     *
     * @var string
     */
    protected $_sUrl = null;

    /**
     *
     * @var SiteQ_Service_Result
     */
    protected $_oResult;

    /**
     * Query a service
     *
     * @return SiteQ_Service_AbstractService
     */
    abstract public function query();

    /**
     * Parse the raw result into SiteQ-Common
     *
     * @return SiteQ_Service_AbstractService
     */
    abstract public function parse();

    /**
     * The init
     */
    public function __construct() {
        $this->_oResult = new SiteQ_Service_Result();
    }

    /**
     * Set the url
     *
     * @param  string $sUrl
     *
     * @return SiteQ_Service_AbstractService
     */
    public function url($sUrl) {
        $this->_sUrl = $sUrl;
        return $this;
    }

    /**
     * Get the Result
     *
     * @return SiteQ_Service_Result
     */
    public function get() {
        return $this->_oResult;
    }
}