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
class MultiLineText extends FormElement
{    
    public function __toString(){
        
        return "Multi-line Text: $this->name";
    }
}
