<?php
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
        if (isset($this->suggestions[$sSeverity])) {
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