<?php

namespace spec\Md\PhunkieConsole\Instruction;

use const Md\Phunkie\Functions\function1\identity;
use Md\PhunkieConsole\Instruction\FunctionDefinitionInstruction;
use Md\PhunkieConsole\Result\FunctionDefinedInstructionResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FunctionDefinitionInstructionSpec extends ObjectBehavior
{
    function it_returns_a_function_defined_result()
    {
        $uniqueFunction = "foo".md5((string)(microtime()));
        $this->beConstructedWith("function {$uniqueFunction}(){}");
        $this->execute()->shouldBeLike(Success(new FunctionDefinedInstructionResult("$uniqueFunction")));
    }

    function it_complains_if_a_function_with_the_same_name_has_already_been_defined()
    {
        $uniqueFunction = "foo".md5((string)microtime());
        eval("function {$uniqueFunction}(){}");
        $this->beConstructedWith("function {$uniqueFunction}(){}");

        $result = $this->execute();
        $result->shouldNotBeRight();

        $e = $result->fold(identity, _);
        $e->getMessage()->shouldBe("Cannot redeclare {$uniqueFunction}, previously declared");
    }
}
