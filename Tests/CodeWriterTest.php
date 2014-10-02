<?php
namespace Plum\Gen\Tests;

use Plum\Gen\CodeWriter;

class CodeWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_use_tab_chars_passed_in_constructor()
    {
        $w = new CodeWriter("\t");
        $w->indent();
        $w->outdent();

        $this->assertEquals(PHP_EOL."\t".PHP_EOL, $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_code()
    {
        $w = new CodeWriter();
        $w->write("hello world");

        $this->assertEquals("hello world", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_codes()
    {
        $codes = ["hello", " ", "world"];

        $w = new CodeWriter();
        $w->write(...$codes);

        $this->assertEquals("hello world", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_code_with_newline()
    {
        $w = new CodeWriter();
        $w->writeln("hello world");

        $this->assertEquals("hello world".PHP_EOL, $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_codes_with_newline()
    {
        $codes = ["hello", " ", "world"];

        $w = new CodeWriter();
        $w->writeln(...$codes);

        $this->assertEquals("hello world".PHP_EOL, $w->bufferedCode());
    }

    /** @test */
    function it_should_increases_the_indentation()
    {
        $w = new CodeWriter();
        $w->indent();
        $w->write("hello world");

        $this->assertEquals("
    hello world", $w->bufferedCode());
    }

    /** @test */
    function it_should_decreases_the_indentation()
    {
        $w = new CodeWriter();
        $w->indent();
        $w->write("hello");
        $w->outdent();
        $w->write("world");

        $this->assertEquals("
    hello
world", $w->bufferedCode());
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     * @dataProvider provideInvalidIndentations
     */
    function it_should_throws_when_indentation_step_is_invalid($step)
    {
        (new CodeWriter())->indent($step);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     * @dataProvider provideInvalidIndentations
     */
    function it_should_throws_when_outdentation_step_is_invalid($step)
    {
        (new CodeWriter())->outdent($step);
    }

    function provideInvalidIndentations()
    {
        return [
            [0], [-1], ["hello world"], [0.999]
        ];
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     * @dataProvider provideNegativeIndentations
     */
    function it_should_throws_when_indentation_level_is_negative($i, $o)
    {
        $w = new CodeWriter();
        $w->indent($i);
        $w->outdent($o);
    }

    function provideNegativeIndentations()
    {
        return [
            [1, 2], [1, 10], [999, 1000]
        ];
    }
}
