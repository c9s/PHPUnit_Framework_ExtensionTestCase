<?php

class EXTUnit_TestRunner
{
    public $program;

    // required extension
    public $extension;

    public $script;

    public $arguments = array();

    public $options = array();

    /**
     * @var bool should we run gdb ?
     *
     * To run phpunit with gdb, we first generate the gdbinit with current php
     * and append the phpunit binary path and script path to the arguments.
     */
    public $enableGdb = false;

    // should we run phpunit ?
    public $enablePHPUnit = false;

    public function __construct() 
    {

    }

    public function enablePHPUnit($bool)
    {
        $this->enablePHPUnit = $bool;
    }

    public function enableGdb($bool)
    {
        $this->enableGdb = $bool;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function setScript($script)
    {
        $this->script = $script;
    }

    public function addArgument($arg)
    {
        $this->arguments[] = $arg;
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

    /**
     * Set PHP Options
     */
    public function setPHPOptions($options)
    {
        $this->options = $options;
    }

    public function getPHPOptionArguments()
    {
        // append option arguments for php
        $args = $this->convertOptionsToArguments($this->options);
        // do not read php.ini config
        $args[] = "-d";
        $args[] = "'extension=" . $this->extension . ".so'";
        $args[] = "-n";
        return $args;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments()
    {
        $args = array();

        // if we enabled phpunit, then phpunit is the argument of php
        if ( $this->enablePHPUnit ) {
            $args[] = findbin("phpunit");
        } elseif ( $this->script ) {
            // script file for php to run
            $args[] = $this->script;
        }
        $args = array_merge($args, $this->arguments);
        return $args;
    }


    /**
     * @param string $program our php binary path.
     */
    public function setProgram($program)
    {
        $this->program = $program;
    }


    public function run()
    {
        if ( $this->enableGdb ) {
            if ( ! file_exists('.gdbinit') ) {
                $inits = $this->getGdbInitLines();
                $init = join("\n",$inits);
                echo "====> .gdbinit\n";
                echo $init, "\n";
                if ( false === file_put_contents(".gdbinit", $init) ) {
                    die("Can not write .gdbinit");
                }
            }
            passthru("gdb");
        } else {
            $command = $this->program 
                    . ' ' . join(' ',$this->getPHPOptionArguments())
                    . ' ' . join(' ',$this->getArguments())
                    ;
            echo $command , "\n";
            passthru($command);
        }
    }

    public function getGdbInitLines()
    {
        $init = array();
        $init[] = "file " . $this->program;
        $init[] = "set args"
                . " " . join(' ', $this->getPHPOptionArguments() )
                . " " . join(' ', $this->getArguments())
                ;
        return $init;
    }
}
