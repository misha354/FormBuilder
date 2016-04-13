<?php

abstract class Lexer {
    const EOF = -1;
    const EOF_TYPE = 1;

    protected $input; //input string
    protected $inputLength;
    protected $c; //the current character
    protected $p = 0; //index into input of current character
    
    public function __construct($input){
        if (empty($input)){
            throw new Exception(' Empty input file ' );
        }
        $this->input = $input;
        $this->c = $this->input[$this->p];
        $this->inputLength = strlen($input);
    }
    
    public function consume(){
        $this->p++;
        if ($this->p >= $this->inputLength){
            $this->c = self::EOF;
        }
        else{
            $this->c = $this->input[$this->p];
        }
    }
    
    public function match($x) {
        if ( $this->c == $x) $this->consume();
        else{ throw new Exception("expecting $x; found $this->c");        
        }
    }

    
    public abstract function nextToken();
    public abstract function getTokenName($tokenType);
}
