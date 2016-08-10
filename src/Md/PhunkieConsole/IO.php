<?php

namespace Md\PhunkieConsole;

use function Md\Phunkie\Functions\io\io;
use Md\Phunkie\Types\ImmList;
use Md\Phunkie\Cats\IO as IOUnit;
use Md\Phunkie\Cats\IO as IOString;

function ReadLine($prompt): IOString
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

function PrintLn($message): IOUnit
{
    return io(function() use ($message) { print($message . "\n"); });
}

function PrintLines(ImmList $lines): IOUnit
{
    return io(function() use ($lines) { $lines->map(function($line) { print($line . "\n"); }); });
}