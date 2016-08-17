<?php

namespace spec\Md\PhunkieConsole\Instruction;

use const Md\Phunkie\Functions\function1\identity;
use Md\PhunkieConsole\Instruction\ClassDefinitionInstruction;
use Md\PhunkieConsole\Result\ClassDefinedInstructionResult;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ClassDefinitionInstruction
 */
class ClassDefinitionInstructionSpec extends ObjectBehavior
{
    function it_returns_a_class_defined_result()
    {
        $uniqueClass = "Boo".md5((string)microtime());
        $this->beConstructedWith("class $uniqueClass{}");
        $this->execute()->shouldBeLike(Success(new ClassDefinedInstructionResult("$uniqueClass")));
    }

    function it_complains_if_a_class_with_the_same_name_has_already_been_defined()
    {
        $uniqueClass = "Boo".md5((string)microtime());
        eval("class $uniqueClass{}");
        $this->beConstructedWith("class $uniqueClass{}");
        $result = $this->execute();
        $result->shouldNotBeRight();

        $e = $result->fold(identity, _);
        $e->getMessage()->shouldBe("Cannot declare class {$uniqueClass}, because the name is already in use");
    }
}
