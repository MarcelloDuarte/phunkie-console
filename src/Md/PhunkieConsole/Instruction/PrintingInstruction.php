<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Types\Option;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class PrintingInstruction extends BasicInstruction
{
    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        ob_start();
        eval(rtrim($this->getInstruction(), ";") . ";");
        $output = ob_get_contents();
        ob_end_clean();
        return Some(new PrintableInstructionResult($output));
    }
}