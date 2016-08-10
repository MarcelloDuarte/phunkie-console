<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Result\ConstantDefinedInstructionResult;

class ConstantDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        preg_match('/(const)(\s+)(\w+)(\s*)=(.*)/', $this->getInstruction(), $matches);
        eval($this->getInstruction() . ";");
        if (in_array($matches[3], get_defined_constants(true)['user'])) {
            return Some(new ConstantDefinedInstructionResult($matches[3]));
        }
        return None();
    }
}