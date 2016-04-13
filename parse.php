<?php

spl_autoload_register(function ($class) {
    include $class . '.php';
});

if (!file_exists($argv[1])){
	die("File $argv[1] doesn't exist.");
}

//$lexer = new FormLexer(file_get_contents($argv[1])); // parse command-line arg
$lexer = new FormLexer(""); // parse command-line arg

$parser = new FormParser($lexer);
$parser->form_elements(); // begin parsing at rule list
print_r($parser->form);