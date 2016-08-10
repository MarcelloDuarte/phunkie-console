<?php

namespace Md\PhunkieConsole\Result;

use function Md\PhunkieConsole\Colors\magenta;

class FunctionDefinedInstructionResult extends InstructionResult
{
    public function output(): string
    {
        return magenta("defined function " . $this->getResult());
    }
}