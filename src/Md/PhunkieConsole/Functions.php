<?php

namespace Md\PhunkieConsole;

use Md\Phunkie\Cats\IO as IOUnit;
use function Md\Phunkie\Functions\io\io;
use function Md\Phunkie\PatternMatching\Referenced\Some as _Some;
use function Md\Phunkie\Functions\show\get_value_to_show;
use Md\Phunkie\Types\ImmList;

set_exception_handler(function(\Throwable $e) {
    PrintLn(get_class($e) . ": " . $e->getMessage())->run();
    forever(io(function() {
        ReadLine("phunkie > ")->flatMap(function ($input) { return ProcessAndOutputResult($input); })->run();
    }));
});

set_error_handler(function($code, $error) {
    $type = "Error";
    switch ($code) {
        case E_NOTICE: $type = "Notice"; break;
        case E_WARNING: $type = "Warning"; break;
    }
    PrintLn("$type: $error")->run();
    forever(io(function() {
        ReadLine("phunkie > ")->flatMap(function ($input) { return ProcessAndOutputResult($input); })->run();
    }));
});

require_once __DIR__ . "/Instruction.php";
require_once __DIR__ . "/Result.php";
require_once __DIR__ . "/Command.php";

function PrintLn($message)
{
    return io(function() use ($message) { print($message . "\n"); });
}

function PrintLines(ImmList $lines)
{
    return io(function() use ($lines) { $lines->map(function($line) { print($line . "\n"); }); });
}

function ReadLine($prompt)
{
    return io(function () use ($prompt) {
        $input = \readline($prompt);
        if (!empty($input)) {
            readline_add_history($input);
        }
        $line = rtrim($input, "\n");
        if ($input === false || $line === "exit") {
            echo $input ? "" : "\n";
            exit(0);
        }
        return $line;
    });
}

function ProcessAndOutputResult($input): IOUnit { $on = match(Instruction($input)->execute()); switch (true) {
    case $on(None): return PrintLn("");
    case $on(_Some($a)): return PrintLn($a->output()); }
    return PrintLn("");
}

function forever(IOUnit $io) {
    $io->run();
    forever ($io);
}