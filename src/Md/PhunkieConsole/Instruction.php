<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Types\Option;

function Instruction($instruction) { switch (true) {
    case empty(trim($instruction)): return new NoInstruction("");
    case isPrinting($instruction): return new PrintingInstruction($instruction);
    case isClass($instruction): return new ClassDefinitionInstruction($instruction);
    case isNamespace($instruction): return new NamespaceDefinitionInstruction($instruction);
    case isFunction($instruction): return new FunctionDefinitionInstruction($instruction);
    case isCommand($instruction): return new CommandInstruction(trim($instruction));
    case isConstant($instruction): return new ConstantDefinitionInstruction(trim($instruction)); }
    return new PlainInstruction($instruction);
}

function isPrinting($instruction) {
    return ImmList("echo", "print", "var_dump", "sprintf", "print_r", "var_export", "die")
        ->filter(function($f) use ($instruction) { return strpos(trim($instruction), $f) === 0; })->length > 0;
}

function isClass($instruction) { return strpos(trim($instruction), "class") === 0; }

function isNamespace($instruction) { return strpos(trim($instruction), "namespace") === 0; }

function isConstant($instruction) {
    return strpos(trim($instruction), "const") === 0 ||
           strpos(trim($instruction), "define(") === 0;
}

function isFunction($instruction) {
    $startsWithFunction = strpos(trim($instruction), "function") === 0;
    $isAnonymousFunction = strpos(trim(substr(trim($instruction), strpos(trim($instruction), "function") + 8)), "(") === 0;
    return $startsWithFunction && !$isAnonymousFunction;
}

function isCommand($instruction) { return strpos(trim($instruction), ":") === 0; }

interface Instruction {
    public function execute(): Option;
}

abstract class BasicInstruction implements Instruction {
    private $instruction;
    public function __construct(string $instruction) { $this->instruction = $instruction; }
    protected function getInstruction() { return $this->instruction; }
}

class NoInstruction extends BasicInstruction {
    public function execute(): Option { return None(); }
}

class PrintingInstruction extends BasicInstruction {
    public function execute(): Option
    {
        ob_start();
        eval(rtrim($this->getInstruction(), ";") . ";");
        $output = ob_get_contents();
        ob_end_clean();
        return Some(new PrintableInstructionResult($output));
    }
}

class ClassDefinitionInstruction extends BasicInstruction {
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

class NamespaceDefinitionInstruction extends BasicInstruction {
    public function execute(): Option
    {
        preg_match('/(namespace)(\s+)([^{]*)({)(.*)}/', $this->getInstruction(), $matches);
        preg_match('/(class)(\s+)(\w+)(.*)/', $matches[5], $classMatches);
        preg_match('/(function)(\s+)(\w+)(.*)/', $matches[5], $functionMatches);
        preg_match('/(const)(\s+)(\w+)(.*)/', $matches[5], $constMatches);
        eval ($this->getInstruction());

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

    /**
     * @param $matches
     * @param $constMatches
     * @return Option
     */
    private function defineConstant($matches, $constMatches)
    {
        $fqcn = "\\" . trim($matches[3]) . "\\" . $constMatches[3];
        if (defined($fqcn)) {
            return Some(new ConstantDefinedInstructionResult($fqcn));
        }
        return None();
    }
}

class FunctionDefinitionInstruction extends BasicInstruction {
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

class ConstantDefinitionInstruction extends BasicInstruction {
    public function execute(): Option
    {
        preg_match('/(const)(\s+)(\w+)(\s*)=(.*)/', $this->getInstruction(), $matches);
        eval($this->getInstruction());
        if (in_array($matches[3], get_defined_constants(true)['user'])) {
            return Some(new ConstantDefinedInstructionResult($matches[3]));
        }
        return None();
    }
}

class PlainInstruction extends BasicInstruction {
    public function execute(): Option
    {
        $veryUniquePhunkieSpecificVariableName = null;
        eval("\$veryUniquePhunkieSpecificVariableName={$this->getInstruction()};");
        return Some(new ShowableInstructionResult($veryUniquePhunkieSpecificVariableName));
    }
}

class CommandInstruction extends BasicInstruction {
    public function execute(): Option
    {
        return Some(new PrintableInstructionResult(Command($this->getInstruction())->execute()->getOrElse("")));
    }
}