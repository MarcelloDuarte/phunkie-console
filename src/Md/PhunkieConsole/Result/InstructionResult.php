<?php

namespace Md\PhunkieConsole\Result;

abstract class InstructionResult
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    abstract public function output(): string;

    protected function getResult()
    {
        return $this->result;
    }
}