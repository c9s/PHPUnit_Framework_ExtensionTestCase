<?php

class EXTUnit_TestSuite
{
    public $program;

    public $arguments = array();


    public function __construct() {  

    }

    public function setArguments($args) 
    {
        $this->arguments = $args;
    }

    public function setProgram($program)
    {
        $this->program = $program;
    }

    public function run()
    {
        $command = $this->program . ' ' . join(' ',$this->arguments);
        system($command);
    }

    public function toGdbInit()
    {
        $init = array();
        $init[] = "file " . findbin($this->program);
        $init[] = "set args " . join(' ', $this->arguments);
        return join("\n", $init);
    }
}
