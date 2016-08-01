<?php

namespace Md\PhunkieConsole;

use function Md\Phunkie\Functions\show\get_value_to_show;

abstract class InstructionResult
{
    private $result;
    public function __construct($result) { $this->result = $result; }
    abstract public function output();
    protected function getResult() { return $this->result; }
}

class ShowableInstructionResult extends InstructionResult {
    public function output() { return get_value_to_show($this->getResult()); }
}

class FunctionDefinedInstructionResult extends InstructionResult {
    public function output() { return "defined function " . $this->getResult(); }
}

class ClassDefinedInstructionResult extends InstructionResult {
    public function output() { return "defined class " . $this->getResult(); }
}

class PrintableInstructionResult extends InstructionResult {
    public function output() { return $this->getResult(); }
}