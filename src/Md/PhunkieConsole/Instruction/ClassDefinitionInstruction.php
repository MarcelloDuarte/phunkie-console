<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Result\ClassDefinedInstructionResult;

class ClassDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        preg_match('/(class)(\s+)(\w+)(.*)/', $this->getInstruction(), $matches);
        eval($this->getInstruction());
        if (class_exists($matches[3])) {
            return Some(new ClassDefinedInstructionResult($matches[3]));
        }
        return None();
    }
}