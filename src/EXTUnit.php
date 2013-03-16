<?php

class EXTUnit
{
    public $dom;

    public $options = array();

    public $enableGdb = false;

    public $enablePHPUnit = false;

    public function __construct($file) {
        $this->dom = new DOMDocument;
        $this->dom->load($file);

        $workdir = getcwd();
        $this->options = array(
            "output_handler"          => "",
            "open_basedir"            => "",
            "safe_mode"               => "0" ,
            "disable_functions"       => "" ,
            "output_buffering"        => "Off" ,
            "error_reporting"         => "32767" ,
            "display_errors"          => "1",
            "display_startup_errors"  => "1",
            "log_errors"              => "0",
            "html_errors"             => "0",
            "track_errors"            => "1",
            "report_memleaks"         => "1",
            "report_zend_debug"       => "0",
            "docref_root"             => "",
            "docref_ext"              => ".html",
            "error_prepend_string"    => "",
            "error_append_string"     => "",
            "auto_prepend_file"       => "",
            "auto_append_file"        => "",
            "magic_quotes_runtime"    => "0",
            "ignore_repeated_errors"  => "0",
            "precision"               => "14",
            "memory_limit"            => "128M",
            "extension_dir"           => "$workdir/modules/" ,
            "session.auto_start"      => "0",
            "zlib.output_compression" => "Off",
            "mbstring.func_overload"  => "0",
        );
    }

    public function setOption($name,$value) 
    {
        $this->options[ $name ] = $value;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getExtensionName()
    {
        $xpath = new DOMXPath($this->dom);
        $exts = array();
        $xext = $xpath->query('extension');
        return $xext->item(0)->textContent;
    }

    public function run($argv)
    {
        $runner = new EXTUnit_TestRunner;
        $runner->setExtension($this->getExtensionName());

        if ( $this->enableGdb ) {
            $runner->enableGdb(true);
        }
        if ( $this->enablePHPUnit ) {
            $runner->enablePHPUnit(true);
        }
        $runner->setProgram( findbin('php') );
        $runner->setPHPOptions($this->options);
        $runner->setArguments($argv);
        $runner->run();
    }

    public function getTestSuites()
    {
        $xpath    = new DOMXPath($this->dom);
        $xtestsuites = $xpath->query('testsuites/testsuite');
        $suites = array();
        foreach( $xtestsuites as $xs ) {
            $testFile      = $xs->getAttribute('file');
            $extensionName = $xs->getAttribute('extension');
            $enableGdb     = $xs->getAttribute('gdb');
            $enablePHPUnit = $xs->getAttribute('phpunit');

            $suite = new EXTUnit_TestSuite;
            $suite->addExtension($extensionName);
            $suite->setScript($testFile);
            if ( $enableGdb ) {
                $suite->enableGdb(true);
            }
            if ( $enablePHPUnit ) {
                $suite->enablePHPUnit(true);
            }

            foreach( $argv as $a ) {
                $suite->addArgument($a);
            }
            $suite->setArguments($args); 
            $suites[] = $suite;
        }
        return $suites;
    }

}


