<?php

namespace Md\PhunkieConsole\Command;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Instruction\BasicInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class HelpCommand extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        return Some(new PrintableInstructionResult(
            " Commands available from the prompt\n".
            "  :import <module>            load module(s) and their dependents\n".
            "  :help, :?                   display this list of commands"
        ));
    }
}