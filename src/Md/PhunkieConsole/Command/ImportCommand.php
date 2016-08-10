<?php

namespace Md\PhunkieConsole\Command;

use Md\Phunkie\Types\Option;
use function Md\PhunkieConsole\import;
use Md\PhunkieConsole\Instruction\BasicInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;

class ImportCommand extends BasicInstruction
{
    const MODULE_NAME_STARTS_HERE = 8;

    /**
     * @return Option<InstructionResult>
     */
    public function execute(): Option
    {
        $module = substr($this->getInstruction(), self::MODULE_NAME_STARTS_HERE);
        return Some(new PrintableInstructionResult(import($module === false ? "" : $module)->getOrElse("")));
    }
}