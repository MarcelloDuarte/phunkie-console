<?php

namespace Md\PhunkieConsole\Instruction;

use Md\Phunkie\Validation\Validation;
use Md\PhunkieConsole\Result\BufferedResult;

class BlockBufferInstruction extends BasicInstruction
{

    public function execute(): Validation
    {
        return Success(new BufferedResult($this->getInstruction()));
    }
}