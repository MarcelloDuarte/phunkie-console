<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Cats\IO;
use function Md\Phunkie\Functions\io\io;
use Md\Phunkie\Types\Unit;

class App
{
    public function main(): Unit
    {
        $this->printHeader()->run();
        forever(io(function() {
            ReadLine("phunkie > ")->flatMap(function ($input) { return ProcessAndOutputResult($input); })->run();
        }));

        return Unit();
    }

    private function printHeader(): IO
    {
        return PrintLines(ImmList(
            "Welcome to phunkie console.",
            "",
            "Type in expressions to have them evaluated.",
            ""
        ));
    }
}
