<?php

namespace Md\PhunkieConsole\Command;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Instruction\BasicInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class HelpCommand extends BasicInstruction
{
     const TEXT = " Commands available from the prompt\n".
     "  :import <module>            load module(s) and their dependents\n".
     "  :help, :?                   display this list of commands";

    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        return Success(new PrintableInstructionResult(self::TEXT));
    }
}