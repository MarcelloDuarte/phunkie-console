<?php

namespace Md\PhunkieConsole\Command;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Instruction\BasicInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class InvalidCommand extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        return Failure(new \Error($this->getInstruction() . " is not a valid command. Type :help for available commands."));
    }
}