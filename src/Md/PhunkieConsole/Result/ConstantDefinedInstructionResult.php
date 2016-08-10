<?php

namespace Md\PhunkieConsole\Result;

use function Md\PhunkieConsole\Colors\magenta;

class ConstantDefinedInstructionResult extends InstructionResult
{
    public function output(): string
    {
        return magenta("defined constant " . $this->getResult());
    }
}