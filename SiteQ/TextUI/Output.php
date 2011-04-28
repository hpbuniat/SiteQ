<?php

class SiteQ_TextUI_Output {

    static public function error($message, $exit = true) {
        print "Error: " . $message . PHP_EOL;
        if ($exit) {
            exit(SiteQ_TextUI_Command::ERROR_EXIT);
        }
    }

    static public function info($message) {
        print $message . PHP_EOL;
    }

    static public function write($message) {
        print $message;
    }
}