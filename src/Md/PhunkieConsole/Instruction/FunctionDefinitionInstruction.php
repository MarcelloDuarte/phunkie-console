<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Result\FunctionDefinedInstructionResult;

class FunctionDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        preg_match('/(function)(\s+)(\w+)(.*)/', $this->getInstruction(), $matches);
        eval($this->getInstruction());
        if (in_array($matches[3], get_defined_functions()['user'])) {
            return Some(new FunctionDefinedInstructionResult($matches[3]));
        }
        return None();
    }
}