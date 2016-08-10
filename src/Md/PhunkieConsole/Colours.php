<?php

namespace Md\PhunkieConsole\Colors;

function boldRed($message)
{
    return bold(red($message));
}

function red($message)
{
    return "\e[31m$message\e[0m";
}

function blue($message)
{
    return "\e[34m$message\e[0m";
}

function bold($message)
{
    return "\e[1m$message\e[21m";
}

function cyan($message)
{
    return "\e[36m$message\e[0m";
}

function magenta($message)
{
    return "\e[35m$message\e[0m";
}

function purple($message)
{
    return "\e[38;5;57m$message\e[0m";
}