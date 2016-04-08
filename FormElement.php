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
class FormElement
{

    public $name;

    //put your code here

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return __CLASS__ . ": $name";
    }

}
