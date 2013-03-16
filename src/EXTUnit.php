<?php

class EXTUnit
{
    public $dom;

    public function __construct($file) {
        $this->dom = new DOMDocument;
        $this->dom->load($file);
    }

    public function getDefaultOptions()
    {
        $phpoptions = array(
            "output_handler"          => "",
            "open_basedir"            => "",
            "safe_mode"               => "0" ,
            "disable_functions"       => "" ,
            "output_buffering"        => "Off" ,
            "error_reporting"         => "32767" ,
            "display_errors"          => "1"  ,
            "display_startup_errors"  => "1" ,
            "log_errors"              => "0"  ,
            "html_errors"             => "0"  ,
            "track_errors"            => "1" ,
            "report_memleaks"         => "1" ,
            "report_zend_debug"       => "0" ,
            "docref_root"             => ""  ,
            "docref_ext"              => ".html" ,
            "error_prepend_string"    => ""  ,
            "error_append_string"     => "" ,
            "auto_prepend_file"       => ""  ,
            "auto_append_file"        => ""  ,
            "magic_quotes_runtime"    => "0" ,
            "ignore_repeated_errors"  => "0"  ,
            "precision"               => "14"  ,
            "memory_limit"            => "128M" ,
            "extension_dir"           => "$workdir/modules/" ,
            "session.auto_start"      => "0" ,
            "zlib.output_compression" => "Off" ,
            "mbstring.func_overload"  => "0" ,
        );
        return $phpoptions;
    }

    public function convertOptionsToArguments($options)
    {
        $args = array();
        foreach($options as $key => $value) {
            $args[] = "-d";
            $args[] = escapeshellarg("$key=$value");
        }
        return $args;
    }

    public function getTestSuites()
    {
        $xpath    = new DOMXPath($this->dom);
        $xtestsuites = $xpath->query('testsuites/testsuite');
        $suites = array();
        foreach( $xtestsuites as $xs ) {
            $testFile      = $xs->getAttribute('file');
            $extensionName = $xs->getAttribute('extension');

            $args = $this->convertOptionsToArguments($this->getDefaultOptions());
            $args[] = "-n"; // do not load php.ini
            $args[] = "-d"; // set option to load the extension from local directory
            $args[] = "extension=$extensionName.so";

            foreach( $argv as $a ) {
                $args[] = $a;
            }

            $suite = new EXTUnit_TestSuite;
            $suite->setArguments($args); 
            $suites[] = $suite;
        }
        return $suites;
    }

}


