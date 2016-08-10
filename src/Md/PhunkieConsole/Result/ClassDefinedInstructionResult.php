<?php

namespace Md\PhunkieConsole\Result;

use function Md\PhunkieConsole\Colors\magenta;

class ClassDefinedInstructionResult extends InstructionResult
{
    public function output(): string
    {
        return magenta("defined class " . $this->getResult());
    }
}