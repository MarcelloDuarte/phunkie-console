<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Types\ImmList;
use Md\Phunkie\Types\NonEmptyList;
use Md\Phunkie\Types\Unit;

use Md\Phunkie\Cats\IO as IOUnit;
use function Md\Phunkie\Functions\io\io;

use function Md\Phunkie\PatternMatching\Referenced\Success as Valid;
use function Md\Phunkie\PatternMatching\Referenced\Failure as Invalid;

use function Md\PhunkieConsole\Colors\bold;
use function Md\PhunkieConsole\Colors\boldRed;
use function Md\PhunkieConsole\Colors\purple;
use Md\PhunkieConsole\Result\BufferedResult;
use Throwable;

require_once __DIR__ . "/Colours.php";
require_once __DIR__ . "/IO.php";
require_once __DIR__ . "/Instruction.php";
require_once __DIR__ . "/Command.php";

function keepDealingWithErrors($state) {
    set_exception_handler(function(Throwable $e) use ($state) {
        PrintLn(bold(get_class($e)) . ": " . boldRed($e->getMessage()))->run();
        keepDealingWithErrors($state);
        readLineProcessAndOutput($state);
    });

    set_error_handler(function($code, $error) use ($state) { switch ($code) {
        case E_NOTICE: $type = "Notice"; break;
        case E_WARNING: $type = "Warning"; break;
        default: $type = "Error"; }

        PrintLn(bold("$type: ") . boldRed($error))->run();
        keepDealingWithErrors($state);
        readLineProcessAndOutput($state);
    });
}

function readLineProcessAndOutput(AppState $state): Unit
{
    forever(io(function() use ($state) {
        ReadLine(bold(purple("phunkie")) . $state->prompt())
            ->flatMap(function ($input) use ($state) { return processAndOutputResult($input, $state); })
            ->run();
    }));
    return Unit();
}

function processAndOutputResult($input, $state): IOUnit { $on = match(Instruction($input)->execute()); switch (true) {
    case $on(Invalid($e)): return $e instanceof NonEmptyList ?
                                  $e->fold("", function($x,Throwable $y) {
                                      return $x instanceof Throwable ? PrintLn(bold(get_class($x)) . ": " . boldRed($x->getMessage()))->andThen(
                                          PrintLn(bold(get_class($y)) . ": " . boldRed($y->getMessage()))) :
                                          PrintLn(bold(get_class($y)) . ": " . boldRed($y->getMessage()));
                                  }) :
                                  PrintLn(bold(get_class($e)) . ": " . boldRed($e->getMessage()));
    case $on(Valid($a)):
        if ($a instanceof BufferedResult) {

        }
        return PrintLn($a->output()); }
    return PrintLn("");
}

function forever(IOUnit $io) {
    $io->run();
    forever ($io);
}