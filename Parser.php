<?php

/***
 * Excerpted from "Language Implementation Patterns",
 * published by The Pragmatic Bookshelf.
 * Copyrights apply to this code. It may not be used to create training material, 
 * courses, books, articles, and the like. Contact us if you are in doubt.
 * We make no guarantees that this code is fit for any purpose. 
 * Visit http://www.pragmaticprogrammer.com/titles/tpdsl for more book information.
***/
abstract class Parser {
    protected $input;     // Lexer from where do we get tokens?
    protected $lookahead; // the current lookahead token
    
    public function __construct(Lexer $input) {
//        echo "parser constructor called\n";
        $this->input = $input; 
        $this->consume();
        }
    /** If lookahead token type matches x, consume & return else error */
    public function match($x) {
        if ( $this->lookahead->type == $x ) $this->consume();
        else throw new Exception("expecting {$this->input->getTokenName($x)}; found $this->lookahead. Line: ". $this->input->getLineNumber());
    }
    
    public function consume() { $this->lookahead = $this->input->nextToken(); 
        print_r("Lookahead is " . $this->lookahead . "\n");
         }
}
