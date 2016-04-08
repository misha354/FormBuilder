<?php

class FormParser extends Parser {
    public $form = array();
    
    public function __construct (Lexer $input) { 
        parent::__construct($input);
    }
    
    public function form_elements(){
        do {
            $this->form[] = $this->form_element();
        } while ($this->lookahead->type == FormLexer::SINGLE_LINE_TEXT ||
               $this->lookahead->type == FormLexer::MULTI_LINE_TEXT ||
               $this->lookahead->type == FormLexer::MULTIPLE_CHOICE );
                
        $this->form[] = $this->submit();        
    }
    
    public function form_element(){
        if ($this->lookahead->type == FormLexer::SINGLE_LINE_TEXT){
            $this->form[] = $this->single_line_text();
        }
        else if ($this->lookahead->type == FormLexer::MULTI_LINE_TEXT){
            $this->form[] = $this->multi_line_text();
        }
        else if ($this->lookahead->type == FormLexer::MULTIPLE_CHOICE){
            $this->form[] = $this->multiple_choice();
        }
      /*  else{
            throw new Exception("Expected a form element, found $this->lookahead->text");
        }*/        
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
        return new Submit($this->quoted_string);
    }            
}
