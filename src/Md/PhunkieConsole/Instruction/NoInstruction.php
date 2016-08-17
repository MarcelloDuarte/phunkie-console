<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\NoResult;

class NoInstruction extends BasicInstruction
{
    /**
     * @return Validation<InstructionResult>
     */
    public function execute(): Validation
    {
        return Success(new NoResult(""));
    }
}