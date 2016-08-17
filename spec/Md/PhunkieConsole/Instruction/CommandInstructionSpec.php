<?php

namespace spec\Md\PhunkieConsole\Instruction;

use Error;
use const Md\Phunkie\Functions\function1\identity;
use function Md\PhunkieConsole\Colors\cyan;
use Md\PhunkieConsole\Command\HelpCommand;
use function Md\PhunkieConsole\import;
use Md\PhunkieConsole\Result\PrintableInstructionResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandInstructionSpec extends ObjectBehavior
{
    function it_has_a_help_command()
    {
        $this->beConstructedWith(":help");
        $validResult = $this->execute();
        $validResult->shouldBeRight();
        $result = $validResult->fold(_, identity);
        $result->shouldHaveType(PrintableInstructionResult::class);
        $result->output()->shouldBe(cyan(HelpCommand::TEXT));
    }

    function it_has_an_import_command()
    {
        $this->beConstructedWith(":import show/show");
        $validResult = $this->execute();
        $validResult->shouldBeRight();
        $result = $validResult->fold(_, identity);
        $result->shouldHaveType(PrintableInstructionResult::class);
    }

    function it_produces_an_invalid_command_on_error()
    {
        $this->beConstructedWith(":invalid");
        $validResult = $this->execute();
        $validResult->shouldNotBeRight();
        $e = $validResult->fold(identity, _);
        $e->shouldHaveType(Error::class);
        $e->getMessage()->shouldBe(":invalid is not a valid command. Type :help for available commands.");
    }
}
