<?php
class FormParserTest extends PHPUnit_Framework_TestCase
{
    private function parse($text){
        $lexer = new FormLexer($text); // parse command-line arg
        $parser = new FormParser($lexer);
        $parser->form_elements(); // begin parsing at rule list
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Empty input file
     */
    public function testEmptyString()
    {
        $this->parse("");
    }
}
?>