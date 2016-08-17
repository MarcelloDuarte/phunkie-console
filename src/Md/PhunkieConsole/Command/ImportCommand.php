<?php

namespace Md\PhunkieConsole\Command;

use Md\Phunkie\Validation\Validation;
use function Md\PhunkieConsole\import;
use Md\PhunkieConsole\Instruction\BasicInstruction;
use Md\PhunkieConsole\Result\PrintableInstructionResult;
use function Md\Phunkie\PatternMatching\Referenced\Some as Maybe;

class ImportCommand extends BasicInstruction
{
    const MODULE_NAME_STARTS_HERE = 8;

    /**
     * @return Validation<Exception, InstructionResult>
     */
    public function execute(): Validation
    {
        $module = substr($this->getInstruction(), self::MODULE_NAME_STARTS_HERE);
        $on = match(import($module === false ? "" : $module)); switch(true) {
            case $on(Maybe($result)): return Success(new PrintableInstructionResult($result));
        }
    }
}