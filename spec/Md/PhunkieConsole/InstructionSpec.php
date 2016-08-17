<?php

namespace spec\Md\PhunkieConsole;

use function \Md\PhunkieConsole\Instruction;
use Md\PhunkieConsole\Instruction\ClassDefinitionInstruction;
use Md\PhunkieConsole\Instruction\CommandInstruction;
use Md\PhunkieConsole\Instruction\ConstantDefinitionInstruction;
use Md\PhunkieConsole\Instruction\FunctionDefinitionInstruction;
use Md\PhunkieConsole\Instruction\NamespaceDefinitionInstruction;
use Md\PhunkieConsole\Instruction\NoInstruction;
use Md\PhunkieConsole\Instruction\PlainInstruction;
use Md\PhunkieConsole\Instruction\PrintingInstruction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InstructionSpec extends ObjectBehavior
{
    function it_creates_a_no_instruction()
    {
        expect(Instruction(""))->toHaveType(NoInstruction::class);
    }

    function it_creates_a_printing_instruction()
    {
        foreach(["echo", "print", "var_dump", "sprintf", "print_r", "var_export", "die"] as $printFunction) {
            expect(Instruction("$printFunction()"))->toHaveType(PrintingInstruction::class);
        }
    }

    function it_creates_a_class_definition_instruction()
    {
        $foo = "Foo" . ((string)microtime());
        expect(Instruction("class $foo{}"))->toHaveType(ClassDefinitionInstruction::class);
    }

    function it_creates_a_function_definition_instruction()
    {
        $foo = "foo" . ((string)microtime());
        expect(Instruction("function $foo(){}"))->toHaveType(FunctionDefinitionInstruction::class);
    }

    function it_creates_a_namespace_definition_instruction()
    {
        $foo = "foo" . ((string)microtime());
        expect(Instruction("namespace $foo{}"))->toHaveType(NamespaceDefinitionInstruction::class);
    }

    function it_creates_a_constant_definition_instruction()
    {
        $foo = "foo" . ((string)microtime());
        expect(Instruction("const $foo = '$foo'"))->toHaveType(ConstantDefinitionInstruction::class);
    }

    function it_creates_a_command_instruction()
    {
        expect(Instruction(":help"))->toHaveType(CommandInstruction::class);
    }

    function it_creates_a_plain_instruction()
    {
        expect(Instruction("42"))->toHaveType(PlainInstruction::class);
    }
}
