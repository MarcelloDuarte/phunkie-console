<?php

namespace Md\PhunkieConsole\Result;

use function Md\PhunkieConsole\Colors\cyan;

class PrintableInstructionResult extends InstructionResult
{
    public function output(): string
    {
        return cyan($this->getResult());
    }
}