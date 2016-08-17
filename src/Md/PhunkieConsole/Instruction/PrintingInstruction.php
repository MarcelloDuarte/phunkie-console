<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class PrintingInstruction extends BasicInstruction
{
    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        ob_start();
        eval(rtrim($this->getInstruction(), ";") . ";");
        $output = ob_get_contents();
        ob_end_clean();
        return Success(new PrintableInstructionResult($output));
    }
}