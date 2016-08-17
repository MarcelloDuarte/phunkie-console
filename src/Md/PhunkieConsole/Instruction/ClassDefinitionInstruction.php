<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\ClassDefinedInstructionResult;
use Md\PhunkieConsole\Result\NoResult;

class ClassDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        preg_match('/(class)(\s+)(\w+)(.*)/', $this->getInstruction(), $matches);
        if (isset($matches[3]) && class_exists($matches[3])) {
            return Failure(new \Error("Cannot declare class {$matches[3]}, because the name is already in use"));
        }

        eval($this->getInstruction());

        return class_exists($matches[3]) ?
            Success(new ClassDefinedInstructionResult($matches[3])):
            Success(new NoResult(""));
    }
}