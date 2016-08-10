<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Cats\IO as IOUnit;
use Md\Phunkie\Types\ImmList;
use Md\Phunkie\Types\Unit;
use function Md\Phunkie\Functions\io\io;
use function Md\Phunkie\PatternMatching\Referenced\Some as _Some;
use function Md\PhunkieConsole\Colors\bold;
use function Md\PhunkieConsole\Colors\boldRed;
use function Md\PhunkieConsole\Colors\purple;

require_once __DIR__ . "/Colours.php";
require_once __DIR__ . "/IO.php";
require_once __DIR__ . "/Instruction.php";
require_once __DIR__ . "/Command.php";

function keepDealingWithErrors() {
    set_exception_handler(function(\Throwable $e) {
        PrintLn(bold(get_class($e)) . ": " . boldRed($e->getMessage()))->run();
        readLineProcessAndOutput();
    });

    set_error_handler(function($code, $error) { switch ($code) {
        case E_NOTICE: $type = "Notice"; break;
        case E_WARNING: $type = "Warning"; break;
        default: $type = "Error"; }

        PrintLn(bold("$type: ") . boldRed($error))->run();
        keepDealingWithErrors();
        readLineProcessAndOutput();
    });
}

function readLineProcessAndOutput(): Unit
{
    forever(io(function() {
        ReadLine(bold(purple("phunkie")) . " > ")
            ->flatMap(function ($input) { return processAndOutputResult($input); })
            ->run();
    }));
    return Unit();
}

function processAndOutputResult($input): IOUnit { $on = match(Instruction($input)->execute()); switch (true) {
    case $on(None): return PrintLn("");
    case $on(_Some($a)): return PrintLn($a->output())->andThen(PrintLn("")); }
    return PrintLn("");
}

function forever(IOUnit $io) {
    $io->run();
    forever ($io);
}