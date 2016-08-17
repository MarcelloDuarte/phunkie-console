<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Cats\IO as IOUnit;
use Md\Phunkie\Types\Unit;
use function Md\Phunkie\Functions\io\io as io;
use function Md\PhunkieConsole\Colors\purple;

class App
{
    public function main(): Unit
    {
        $state = new AppState(" > ");
        keepDealingWithErrors($state);
        $this->printHeader()->run();
        return readLineProcessAndOutput($state);
    }

    private function printHeader(): IOUnit
    {
        return PrintLines(ImmList(
            "Welcome to " . purple("phunkie") . " console.",
            "",
            "Type in expressions to have them evaluated.",
            ""
        ));
    }
}
