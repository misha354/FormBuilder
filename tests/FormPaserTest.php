<?php
class FormParserTest extends PHPUnit_Framework_TestCase
{
    private $formParser;

    private function parse($text){
        $lexer = new FormLexer($text); // parse command-line arg
        $this->formParser = new FormParser($lexer);
        $this->formParser->form_elements(); // begin parsing at rule list
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Empty input file
     */
    public function testEmptyString()
    {
        $this->parse("");
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Unterminated string. Line: 1
     */
    public function testUnterminatedString(){
    $text = <<<EOD
SINGLE_LINE_TEXT "temperature
SUBMIT "Click me"
EOD;

    $this->parse($text);

    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Unknown keyword UNKNOWN_KEYWORD. Line: 1
     */
    public function testUnknownKeyword(){
    $text = <<<EOD
UNKNOWN_KEYWORD "temperature"
SUMIT "Click me"
EOD;

    $this->parse($text);

    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage expecting SUBMIT; found <'Click me', QUOTED_STRING>. Line: 2
     */
    public function testMissingSubmit(){
        $text = <<<EOD
SINGLE_LINE_TEXT "temperature"
"Click me"
EOD;

    $this->parse($text);

    }

    public function successfulParse(){
    $text = <<<EOD
UNKNOWN_KEYWORD "temperature"
SUMIT "Click me"
EOD;

        $expected = array();
        $expected[0] = new SingleLineText("temperature");
        $expected[1] = new Submit("Click me");

        $this->parse();

        $this->assertEquals($expected, $this->formParser->form);
    }

}