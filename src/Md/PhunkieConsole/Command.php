<?php

namespace Md\PhunkieConsole;

use function Md\Phunkie\Functions\show\get_value_to_show;
use Md\Phunkie\Types\Option;

function Command($command) { switch(true) {
    case strpos($command, ":import") === 0: return new ImportCommand($command);
    case trim($command) === ":help" || trim($command) === ":?": return new HelpCommand($command);
    default: return new InvalidCommand($command);}
}

class ImportCommand extends BasicInstruction {
    const MODULE_NAME_STARTS_HERE = 8;

    public function execute(): Option
    {
        $module = substr($this->getInstruction(), self::MODULE_NAME_STARTS_HERE);
        return import($module === false ? "" : $module);
    }
}

class HelpCommand extends BasicInstruction {

    public function execute(): Option
    {
        return Some(
            " Commands available from the prompt\n".
            "  :import <module>            load module(s) and their dependents\n".
            "  :help, :?                   display this list of commands"
        );
    }
}

class InvalidCommand extends BasicInstruction {
    public function execute(): Option
    {
        return Some($this->getInstruction() . " is not a valid command. Type :help for available commands.");
    }
}

function import(string $module) {
    $module = trim($module);
    if (count($parts = explode("/", $module)) != 2) {
        return Some("Invalid module: " . get_value_to_show(trim($module)));
    }

    $namespace = "Md\\Phunkie\\Functions\\$parts[0]";
    $function = $parts[1];

    $result = "";
    $createFunction = function($namespace, $function) use (&$result) {
        if (function_exists($function)) {
            $result .= "Function $function already in scope\n";
            return;
        }
        eval ("
            function $function(...\$args) {
                return call_user_func_array('\\$namespace\\$function', \$args);
            }
    \n");
        $result .= "Imported function \\$namespace\\$function()\n";
    };

    if ($function === "*") {
        ImmList(...get_defined_functions()["user"])->filter(function($f) use ($namespace) { return strpos(strtolower($f),strtolower($namespace)) === 0;})
            ->map(function($f){ return substr(strrchr($f, "\\"), 1); })
            ->map(function($function) use ($namespace, $createFunction) { $createFunction($namespace, $function); });
    } else {
        $createFunction($namespace, $function);
    }
    return Some($result);
}