<?php

namespace Md\PhunkieConsole\Result;

class NoResult extends InstructionResult
{
    public function output(): string
    {
        return "";
    }
}