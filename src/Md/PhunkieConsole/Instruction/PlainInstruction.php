<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use Md\Phunkie\Types\Unit;
use Md\PhunkieConsole\Result\PrintableInstructionResult;
use Md\PhunkieConsole\Result\ShowableInstructionResult;

class PlainInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        $veryUniquePhunkieSpecificVariableName = null;
        eval("\$veryUniquePhunkieSpecificVariableName={$this->getInstruction()};");
        if ($veryUniquePhunkieSpecificVariableName instanceof Unit) {
            return Some(new PrintableInstructionResult(""));
        }
        return Some(new ShowableInstructionResult($veryUniquePhunkieSpecificVariableName));
    }
}