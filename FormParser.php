<?php

/*
TODO:
1. Title and Description
2. Sections
*/
class FormParser extends Parser {
    public $form = array();
    
    public function __construct (Lexer $input) { 
        parent::__construct($input);
    }

    private function addElement($element){
        $this->form[] = $element;
    }
    
    public function form_elements(){
        do {
            $this->addElement($this->form_element());
        } while ($this->lookahead->type == FormLexer::SINGLE_LINE_TEXT ||
               $this->lookahead->type == FormLexer::MULTI_LINE_TEXT ||
               $this->lookahead->type == FormLexer::MULTIPLE_CHOICE );
                
        $this->addElement($this->submit());        
    }
    
    public function form_element(){        
        if ($this->lookahead->type == FormLexer::SINGLE_LINE_TEXT){
            return $this->single_line_text();
        }
        else if ($this->lookahead->type == FormLexer::MULTI_LINE_TEXT){
            return $this->multi_line_text();
        }
        else if ($this->lookahead->type == FormLexer::MULTIPLE_CHOICE){
            return $this->multiple_choice();
        }
        else{
            $this->throwException("Expected one of {SINGLE_LINE_INPUT, MULTI_LINE_INPUT, MULTIPLE_CHOICE}. Found $this->lookahead.");
        }
    }
    
    public function single_line_text(){
        $this->match(FormLexer::SINGLE_LINE_TEXT);
        return new SingleLineText($this->quoted_string());        
    }    
    
    public function multi_line_text(){
        $this->match(FormLexer::MULTI_LINE_TEXT);
        return MultiLineText($this->quoted_string());
    }

    public function multiple_choice(){
        $this->input->match(FormLexer::MULTIPLE_CHOICE);
        
        $element = new MultipleChoice($this->quoted_string());

        $element->choices[] = $this->choice();
        $element->choices[] = $this->choice();
        
        while ($this->lookahead == FormLexer::CHOICE){
            $element->choices[] = $this->choice();
        }
        return $element;
    }
    
    public function choice(){
        $this->match(FormLexer::CHOICE);
        return $this->quoted_string();
    }
 
    public function quoted_string(){

        $text = $this->lookahead->text;
        $this->match(FormLexer::QUOTED_STRING);
        
        return $text;        
    }
    
    public function submit(){
        $this->match(FormLexer::SUBMIT);
        return new Submit($this->quoted_string());
    }            
}
