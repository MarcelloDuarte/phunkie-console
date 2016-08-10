<?php

namespace Md\PhunkieConsole\Command;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Instruction\BasicInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class InvalidCommand extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        return Some(new PrintableInstructionResult($this->getInstruction() . " is not a valid command. Type :help for available commands."));
    }
}