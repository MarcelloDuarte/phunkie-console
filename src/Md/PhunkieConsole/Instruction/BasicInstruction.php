<?php

namespace Md\PhunkieConsole\Instruction;

use Md\PhunkieConsole\Instruction;

abstract class BasicInstruction implements Instruction
{
    private $instruction;

    public function __construct(string $instruction)
    {
        $this->instruction = $instruction;
    }

    protected function getInstruction(): string
    {
        return $this->instruction;
    }
}