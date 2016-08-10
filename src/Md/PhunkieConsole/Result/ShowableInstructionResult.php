<?php

namespace Md\PhunkieConsole\Result;

use function Md\Phunkie\Functions\show\get_type_to_show;
use function Md\Phunkie\Functions\show\get_value_to_show;
use function Md\PhunkieConsole\Colors\bold;
use function Md\PhunkieConsole\Colors\magenta;

class ShowableInstructionResult extends InstructionResult
{
    public function output(): string
    {
        return magenta(bold(get_type_to_show($this->getResult()))) .
               " = " . bold(get_value_to_show($this->getResult()));
    }
}