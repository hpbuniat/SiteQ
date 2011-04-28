#!/usr/bin/env php
<?php
if (strpos('@php_bin@', '@php_bin') === 0) {
    set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
}

// lightweight autoloader
function __autoload($sClass) {
    $sClass = str_replace('_', DIRECTORY_SEPARATOR, $sClass);
    require_once $sClass . '.php';
}

SiteQ_TextUI_Command::main();
