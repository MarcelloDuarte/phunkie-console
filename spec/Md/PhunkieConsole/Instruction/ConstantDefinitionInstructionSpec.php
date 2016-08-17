<?php

namespace spec\Md\PhunkieConsole\Instruction;

use const Md\Phunkie\Functions\function1\identity;
use Md\PhunkieConsole\Result\ConstantDefinedInstructionResult;
use PhpSpec\ObjectBehavior;

class ConstantDefinitionInstructionSpec extends ObjectBehavior
{
    function it_returns_a_constant_defined_result()
    {
        $uniqueConstant = "Foo".md5((string)(microtime()));
        $this->beConstructedWith("const {$uniqueConstant} = \"Foo\"");
        $this->execute()->shouldBeLike(Success(new ConstantDefinedInstructionResult("$uniqueConstant")));
    }

    function it_complains_if_a_constant_with_the_same_name_has_already_been_defined()
    {
        $uniqueConstant = "Boo".md5((string)microtime());
        eval("const $uniqueConstant = 'ChuckNorris';");
        $this->beConstructedWith("const $uniqueConstant = 'ChuckNorris'");
        $result = $this->execute();
        $result->shouldNotBeRight();

        $e = $result->fold(identity, _);
        $e->getMessage()->shouldBe("Constant {$uniqueConstant} already defined");
    }
}