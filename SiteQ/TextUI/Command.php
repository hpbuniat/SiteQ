<?php

class SiteQ_TextUI_Command {

    const SUCCESS_EXIT = 0;

    const ERROR_EXIT = 1;

    /**
     * @var array
     */
    protected $arguments = array(
        'verbose' => false,
        'report' => array(
            'console' => 'php://STDOUT'
        ),
        'service' => array(
            'pagespeed'
        ),
        'quiet' => false,
        'url' => ''
    );

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $longOptions = array(
        'keeptype' => NULL,
        'report-html=' => NULL,
        'report-xml=' => NULL,
        'report-json=' => NULL,
        'url=' => NULL,
        'quiet' => NULL,
        'help' => NULL,
        'version' => NULL
    );

    /**
     * @param boolean $exit
     */
    public static function main($exit = TRUE) {
        $command = new SiteQ_TextUI_Command();
        $command->run($_SERVER['argv'], $exit);
    }

    /**
     * Run SiteQ
     *
     * @param  array   $argv
     * @param  boolean $exit
     *
     * @return SiteQ_TextUI_Command
     */
    public function run(array $argv, $exit = TRUE) {
        $this->handleArguments($argv);

        $aServices = array(
            new SiteQ_Service_Pagespeed()
        );

        $collector = new SiteQ_Collector();
        $collector->set($this->arguments, $aServices)->run();

        return $this;
    }

    /**
     * Handle passed arguments
     *
     * @param array $argv
     *
     * @return SiteQ_TextUI_Command
     */
    protected function handleArguments(array $argv) {
        print SiteQ_TextUI_Command::printVersionString();

        try {
            $this->options = Console_Getopt::getopt($argv, 'p:', array_keys($this->longOptions));
        }
        catch (RuntimeException $e) {
            SiteQ_TextUI_Output::info($e->getMessage());
        }

        if ($this->options instanceof PEAR_Error) {
            SiteQ_TextUI_Output::error($this->options->getMessage());
        }

        foreach ($this->options[0] as $option) {

            switch ($option[0]) {
                case '--verbose':
                    {
                        $this->arguments['verbose'] = TRUE;
                    }
                    break;

                case '--quiet':
                    {
                        unset($this->arguments['report']['console']);
                    }

                    break;

                case '--help':
                    {
                        $this->showHelp();
                        exit(SiteQ_TextUI_Command::SUCCESS_EXIT);
                    }

                    break;

                case '--report-html':
                    {
                        $this->arguments['report']['html'] = $option[1];
                    }

                    break;

                case '--report-xml':
                    {
                        $this->arguments['report']['xml'] = $option[1];
                    }

                    break;

                case '--report-json':
                    {
                        $this->arguments['report']['json'] = $option[1];
                    }

                    break;

                case '--version':
                    {
                        exit(SiteQ_TextUI_Command::SUCCESS_EXIT);
                    }

                    break;
            }
        }

        if (! isset($this->options[1][0])) {
            $this->showHelp();
            SiteQ_TextUI_Output::error('URL expected');
        }
        else {
            $this->arguments['url'] = $this->options[1][0];
        }

        return $this;
    }

    /**
     * Show the help message.
     */
    protected function showHelp() {
        print <<<EOT
Usage: SiteQ [switches] URL

  --report-xml <file>       Report in XML format to file.
  --report-json <file>      Report in JSON format to file.
  --report-html <directory> Report in HTML format to directory.

  --quiet                   Be quiet
  --help                    Prints this usage information.
  --version                 Prints the version and exits.

EOT;
    }

    /**
     * Print the version string
     */
    public static function printVersionString() {
        print 'SiteQ - a Website-Quality-Measurement-Tools-Collector (Version: @package_version@)' . PHP_EOL . PHP_EOL;
    }
}