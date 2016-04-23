<?php

abstract class Lexer {
    const EOF = -1;
    const EOF_TYPE = 1;

    protected $input; //input string
    protected $inputLength;
    protected $c; //the current character
    protected $p = 0; //index into input of current character
    
    protected $lineNumber = 1;
    protected $columnNumber = 1;

    public function getLineNumber(){
        return $this->lineNumber;
    }

    public function getColumnNumber(){
        return $this->columnNumber;
    }

    public function __construct($input){
        if (empty($input)){
            throw new Exception(' Empty input file ' );
        }
        $this->input = $input;
        $this->c = $this->input[$this->p];
        $this->inputLength = strlen($input);
    }
    
    public function consume(){
        if ($this->c == "\n"){
            $this->lineNumber++;
        }

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
        else{ throw new $this->throwException("expecting $x; found $this->c.");        
        }
    }

    
    public abstract function nextToken();
    public abstract function getTokenName($tokenType);

    protected function throwException($message){
        throw new Exception($message . " Line: $this->lineNumber");
    }
}
