<?php

namespace Md\PhunkieConsole\Result;

class BufferedResult extends InstructionResult
{
    public function output(): string
    {
        return $this->getResult();
    }
}