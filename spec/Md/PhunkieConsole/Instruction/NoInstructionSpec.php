<?php

namespace spec\Md\PhunkieConsole\Instruction;

use Md\PhunkieConsole\Instruction\NoInstruction;
use Md\PhunkieConsole\Result\NoResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoInstructionSpec extends ObjectBehavior
{
    function it_returns_None_for_empty_input()
    {
        $this->beConstructedWith("");
        $this->execute()->shouldBeLike(Success(new NoResult("")));
    }
}
