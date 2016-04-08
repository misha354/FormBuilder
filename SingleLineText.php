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
class SingleLineText extends FormElement
{    
    public function __toString(){
        
        return "Single Line Text: $this->name";
    }
}
