<?php

namespace Md\PhunkieConsole;

class AppState
{
    private $prompt;
    private $variables;
    private $block;

    public function __construct(string $prompt, array $variables = [], array $block = [])
    {
        $this->prompt = $prompt;
        $this->variables = $variables;
        $this->block = $block;
    }

    public function prompt()
    {
        return $this->prompt;
    }
}