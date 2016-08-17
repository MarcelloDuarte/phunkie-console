<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\ClassDefinedInstructionResult;
use Md\PhunkieConsole\Result\ConstantDefinedInstructionResult;
use Md\PhunkieConsole\Result\FunctionDefinedInstructionResult;
use Md\PhunkieConsole\Result\NoResult;

class NamespaceDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        preg_match('/(namespace)(\s+)([^{]*)({)(.*)}/', $this->getInstruction(), $matches);
        preg_match('/(class)(\s+)(\w+)(.*)/', $matches[5], $classMatches);
        preg_match('/(function)(\s+)(\w+)(.*)/', $matches[5], $functionMatches);
        preg_match('/(const)(\s+)(\w+)(.*)/', $matches[5], $constMatches);
        eval ($this->getInstruction() . ";");

        if (isset($classMatches[3])) {
            return $this->defineClass($matches, $classMatches);
        }

        if (isset($functionMatches[3])) {
            return $this->defineFunction($matches, $functionMatches);
        }

        if (isset($constMatches[3])) {
            return $this->defineConstant($matches, $constMatches);
        }

        return Success(new NoResult(""));
    }

    private function defineClass($matches, $classMatches): Validation
    {
        $fqcn = "\\" . trim($matches[3]) . "\\" . $classMatches[3];
        if (class_exists($fqcn)) {
            return Success(new ClassDefinedInstructionResult($fqcn));
        }
        return Success(new NoResult(""));
    }

    private function defineFunction($matches, $functionMatches): Validation
    {
        $fqfn = "\\" . trim($matches[3]) . "\\" . $functionMatches[3];
        if (function_exists($fqfn)) {
            return Success(new FunctionDefinedInstructionResult($fqfn));
        }
        return Success(new NoResult(""));
    }

    private function defineConstant($matches, $constMatches): Validation
    {
        $fqcn = "\\" . trim($matches[3]) . "\\" . $constMatches[3];
        if (defined($fqcn)) {
            return Success(new ConstantDefinedInstructionResult($fqcn));
        }
        return Success(new NoResult(""));
    }
}