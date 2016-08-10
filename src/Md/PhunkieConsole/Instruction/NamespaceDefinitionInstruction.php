<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Result\ClassDefinedInstructionResult;
use Md\PhunkieConsole\Result\ConstantDefinedInstructionResult;
use Md\PhunkieConsole\Result\FunctionDefinedInstructionResult;

class NamespaceDefinitionInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
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

        return None();
    }

    private function defineClass($matches, $classMatches)
    {
        $fqcn = "\\" . trim($matches[3]) . "\\" . $classMatches[3];
        if (class_exists($fqcn)) {
            return Some(new ClassDefinedInstructionResult($fqcn));
        }
        return None();
    }

    private function defineFunction($matches, $functionMatches)
    {
        $fqfn = "\\" . trim($matches[3]) . "\\" . $functionMatches[3];
        if (function_exists($fqfn)) {
            return Some(new FunctionDefinedInstructionResult($fqfn));
        }
        return None();
    }

    private function defineConstant($matches, $constMatches)
    {
        $fqcn = "\\" . trim($matches[3]) . "\\" . $constMatches[3];
        if (defined($fqcn)) {
            return Some(new ConstantDefinedInstructionResult($fqcn));
        }
        return None();
    }
}