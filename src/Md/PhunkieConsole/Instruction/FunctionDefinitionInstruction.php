<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\FunctionDefinedInstructionResult;
use Md\PhunkieConsole\Result\NoResult;

class FunctionDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        preg_match('/(function)(\s+)(\w+)(.*)/', $this->getInstruction(), $matches);
        if (isset($matches[3]) && function_exists($matches[3])) {
            return Failure(new \Error("Cannot redeclare {$matches[3]}, previously declared"));
        }

        eval($this->getInstruction());

        return in_array($matches[3], get_defined_functions()['user']) ?
            Success(new FunctionDefinedInstructionResult($matches[3])) :
            Success(new NoResult(""));
    }
}