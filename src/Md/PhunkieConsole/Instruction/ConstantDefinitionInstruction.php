<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\ConstantDefinedInstructionResult;

class ConstantDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        preg_match('/(const)(\s+)(\w+)(\s*)=(.*)/', $this->getInstruction(), $matches);

        if (isset($matches[3]) && defined($matches[3])) {
            return Failure(new \Error("Constant {$matches[3]} already defined"));
        }

        eval($this->getInstruction() . ";");

        if (isset($matches[3]) && defined($matches[3])) {
            return Success(new ConstantDefinedInstructionResult($matches[3]));
        }
        return Failure(new \Error("Could not create constant"));
    }
}