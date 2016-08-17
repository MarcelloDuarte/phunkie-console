<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Unit;
use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\PrintableInstructionResult;
use Md\PhunkieConsole\Result\ShowableInstructionResult;

class PlainInstruction extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        $veryUniquePhunkieSpecificVariableName = null;
        eval("\$veryUniquePhunkieSpecificVariableName={$this->getInstruction()};");
        if ($veryUniquePhunkieSpecificVariableName instanceof Unit) {
            return Success(new PrintableInstructionResult(""));
        }
        return Success(new ShowableInstructionResult($veryUniquePhunkieSpecificVariableName));
    }
}