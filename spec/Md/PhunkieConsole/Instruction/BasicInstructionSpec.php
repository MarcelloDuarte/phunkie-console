<?php

namespace spec\Md\PhunkieConsole\Instruction;

use const Md\Phunkie\Functions\function1\identity;
use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Instruction\BasicInstruction as AbstractBasicInstruction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BasicInstructionSpec extends ObjectBehavior
{
    function it_is_constructed_with_instruction()
    {
        $this->beAnInstanceOf(BasicInstruction::class);
        $this->beConstructedWith("some instruction");

        $result = $this->execute();
        $result->fold(_,identity)->shouldBe("some instruction");
    }
}

class BasicInstruction extends AbstractBasicInstruction {
    public function execute(): Validation { return Success($this->getInstruction()); }
}
