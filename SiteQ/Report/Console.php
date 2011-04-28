<?php

class SiteQ_Report_Console extends SiteQ_Report_AbstractReport {

    /**
     * (non-PHPdoc)
     * @see SiteQ/Report/SiteQ_Report_AbstractReport::write()
     */
    public function write($aResults) {
        $report = str_repeat('-', 100) . PHP_EOL;
        $report .= str_pad('Scores: ', 15, ' ');
        foreach ($aResults as $oResult) {
            $report .= str_pad($oResult->name, 15, ' ', STR_PAD_LEFT) . ': ' . str_pad($oResult->score, 3, ' ');
        }

        $report .= PHP_EOL . PHP_EOL;

        foreach ($aResults as $oResult) {
            $report .= str_repeat('-', 100) . PHP_EOL;
            $report .= str_pad('Details for: ' . $oResult->name, 25, ' ');
            $report .= PHP_EOL;
            foreach ($oResult->get() as $sSeverity => $aEntries) {
                foreach ($aEntries as $aEntry) {
                    $report .= str_pad($aEntry['text'] . ' (' . $sSeverity . ')', 50, ' ');
                    $report .= PHP_EOL;
                    if (empty($aEntry['details']) !== true) {
                        foreach ($aEntry['details'] as $aDetail) {
                            $report .= str_pad('= ', 5, ' ', STR_PAD_LEFT);
                            $report .= str_pad($aDetail['text'], 95, ' ');
                            $report .= PHP_EOL;
                            if (empty($aDetail['info']) !== true) {
                                foreach ($aDetail['info'] as $sInfo) {
                                    $report .= str_pad('==> ', 10, ' ', STR_PAD_LEFT);
                                    $report .= str_pad($sInfo, 80, ' ');
                                    $report .= PHP_EOL;
                                }
                            }
                        }
                    }

                    $report .= PHP_EOL;
                }
            }
        }

        $report .= str_repeat('-', 100) . PHP_EOL;

        print $report;
    }
}