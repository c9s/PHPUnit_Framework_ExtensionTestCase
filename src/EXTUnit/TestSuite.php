<?php

class EXTUnit_TestSuite
{
    public $program;

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
    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments()
    {

        // append option arguments for php
        $optionArgs = $this->convertOptionsToArguments($this->options);
        $args = array_merge($args, $optionArgs);

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
        } else {
            $command = $this->program . ' ' . join(' ',$this->getArguments());
            system($command);
        }
    }

    public function toGdbInit()
    {
        $init = array();
        $init[] = "file " . findbin($this->program);
        $init[] = "set args " . join(' ', $this->getArguments());
        // TODO:
        // support breakpoints
        return join("\n", $init);
    }
}
