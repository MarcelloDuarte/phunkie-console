<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Instruction\BlockBufferInstruction;
use Md\PhunkieConsole\Instruction\ClassDefinitionInstruction;
use Md\PhunkieConsole\Instruction\CommandInstruction;
use Md\PhunkieConsole\Instruction\ConstantDefinitionInstruction;
use Md\PhunkieConsole\Instruction\FunctionDefinitionInstruction;
use Md\PhunkieConsole\Instruction\NamespaceDefinitionInstruction;
use Md\PhunkieConsole\Instruction\NoInstruction;
use Md\PhunkieConsole\Instruction\PlainInstruction;
use Md\PhunkieConsole\Instruction\PrintingInstruction;

interface Instruction
{
    public function execute(): Validation;
}

function Instruction($instruction): Instruction { switch (true) {
    case empty(trim($instruction)): return new NoInstruction("");
    case isBlock($instruction): return new BlockBufferInstruction($instruction);
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

function isBlock($instruction) {
    return trim($instruction)[strlen($instruction) - 1] === "{";
}

function isClass($instruction) { return strpos(trim($instruction), "class") === 0; }

function isNamespace($instruction) { return strpos(trim($instruction), "namespace") === 0; }

function isConstant($instruction) {
    return strpos(trim($instruction), "const") === 0 ||
           strpos(trim($instruction), "define") === 0;
}

function isFunction($instruction) {
    $startsWithFunction = strpos(trim($instruction), "function") === 0;
    $isAnonymousFunction = strpos(trim(substr(trim($instruction), strpos(trim($instruction), "function") + 8)), "(") === 0;
    return $startsWithFunction && !$isAnonymousFunction;
}

function isCommand($instruction) { return strpos(trim($instruction), ":") === 0; }
