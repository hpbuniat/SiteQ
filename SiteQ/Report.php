<?php

class SiteQ_Report {

    /**
     * Write a report
     *
     * @param  string $type
     * @param  string $filename
     * @param  array $data
     * @param  array $arguments
     *
     * @return void
     */
    static public function write($type, $filename, $data, $arguments) {
        $reportName = 'SiteQ_Report_' . ucfirst($type);
        $report = new $reportName($filename, $arguments);
        $report->write($data);
    }
}