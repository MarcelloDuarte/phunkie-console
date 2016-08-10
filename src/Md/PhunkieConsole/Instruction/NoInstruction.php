<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;

class NoInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        return None();
    }
}