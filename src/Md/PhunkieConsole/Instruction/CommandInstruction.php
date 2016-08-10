<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use function Md\PhunkieConsole\Command;

class CommandInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        return Command($this->getInstruction())->execute();
    }
}