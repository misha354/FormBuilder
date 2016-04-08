<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MultipleChoice
 *
 * @author mike.zhukovskiy
 */
class MultipleChoice extends FormElement
{
    public $choices = array();
    //put your code here
    
    public function __toString(){
        
        return "Multiple Choice: $this->name\nChoices:\n" . print_r($this->choices, true);
    }
}
