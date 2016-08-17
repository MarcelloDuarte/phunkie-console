<?php

namespace Md\PhunkieConsole;

use function Md\Phunkie\Functions\show\get_value_to_show;
use Md\Phunkie\Types\Option;
use function Md\PhunkieConsole\Colors\bold;
use function Md\PhunkieConsole\Colors\magenta;
use function Md\PhunkieConsole\Colors\red;
use Md\PhunkieConsole\Command\HelpCommand;
use Md\PhunkieConsole\Command\ImportCommand;
use Md\PhunkieConsole\Command\InvalidCommand;

function Command($command) { switch(true) {
    case strpos($command, ":import") === 0: return new ImportCommand($command);
    case trim($command) === ":help" || trim($command) === ":?": return new HelpCommand($command);
    default: return new InvalidCommand($command);}
}

function import(string $module): Option {
    $module = trim($module);
    if (count($parts = explode("/", $module)) != 2) {
        return Some(bold(red("Invalid module: ")) . red(get_value_to_show(trim($module))));
    }

    $namespace = "Md\\Phunkie\\Functions\\$parts[0]";
    $function = $parts[1];

    $result = "";
    $createFunction = function($namespace, $function) use (&$result) {
        if (function_exists($function)) {
            $result .= red("Function $function already in scope\n");
            return;
        }
        eval ("function $function(...\$args) { return call_user_func_array('\\$namespace\\$function', \$args); }");
        $result .= magenta("Imported function \\$namespace\\$function()\n");
    };

    if ($function === "*") {
        ImmList(...get_defined_functions()["user"])
            ->filter(function($f) use ($namespace) { return strpos(strtolower($f),strtolower($namespace)) === 0;})
            ->map(function($f){ return substr(strrchr($f, "\\"), 1); })
            ->map(function($function) use ($namespace, $createFunction) { $createFunction($namespace, $function); });
    } else {
        $createFunction($namespace, $function);
    }
    return Some($result);
}