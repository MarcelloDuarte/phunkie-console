<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Types\Option;

function Instruction($instruction) { switch (true) {
    case empty(trim($instruction)): return new NoInstruction("");
    case isPrinting($instruction): return new PrintingInstruction($instruction);
    case isClass($instruction): return new ClassDefinitionInstruction($instruction);
    case isFunction($instruction): return new FunctionDefinitionInstruction($instruction);
    case isCommand($instruction): return new CommandInstruction($instruction); }
    return new PlainInstruction($instruction);
}

function isPrinting($instruction) {
    return ImmList("echo", "print", "var_dump", "sprintf", "print_r", "var_export", "die")
        ->filter(function($f) use ($instruction) { return strpos(trim($instruction), $f) === 0; })->length > 0;
}

function isClass($instruction) { return strpos(trim($instruction), "class") === 0; }

function isFunction($instruction) { return strpos(trim($instruction), "function") === 0; }

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