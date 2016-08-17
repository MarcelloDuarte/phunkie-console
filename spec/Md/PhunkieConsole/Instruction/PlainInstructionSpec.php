<?php

namespace spec\Md\PhunkieConsole\Instruction;

use Md\PhunkieConsole\Instruction\PlainInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;
use Md\PhunkieConsole\Result\ShowableInstructionResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PlainInstructionSpec extends ObjectBehavior
{
    function it_returns_a_empty_printable_result_for_unit()
    {
        $this->beConstructedWith("Unit()");
        $this->execute()->shouldBeLike(Success(new PrintableInstructionResult("")));
    }

    function it_returns_the_showable_result_for_showable()
    {
        $this->beConstructedWith("ImmList(1,2,3)");
        $this->execute()->shouldBeLike(Success(new ShowableInstructionResult(ImmList(1,2,3))));
    }
}
