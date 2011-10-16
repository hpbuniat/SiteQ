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
 * Console-Reporter
 *
 * @author Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @copyright 2011 Hans-Peter Buniat <hpbuniat@googlemail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version Release: @package_version@
 * @link https://github.com/hpbuniat/SiteQ
 */
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