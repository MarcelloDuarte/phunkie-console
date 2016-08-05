<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Cats\IO as IOUnit;
use function Md\Phunkie\Functions\io\io as io;
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

    private function printHeader(): IOUnit
    {
        return PrintLines(ImmList(
            "Welcome to phunkie console.",
            "",
            "Type in expressions to have them evaluated.",
            ""
        ));
    }
}
