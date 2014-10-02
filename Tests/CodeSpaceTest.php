<?php
namespace Plum\Gen\Tests;

use Plum\Gen\CodeSpace;
use Plum\Gen\CodeWriter;

class CodeSpaceTest extends \PHPUnit_Framework_TestCase
{
    private $dir;

    /**
     * @var CodeSpace
     */
    private $space;

    /** @before */
    function setUp()
    {
        $this->dir = rtrim(sys_get_temp_dir(), "/")."/";
        $this->space = new CodeSpace($this->dir);
    }

    /** @after */
    function tearDown()
    {
        $this->space->clear();
    }

    /** @test */
    function it_should_saves_the_file()
    {
        $w = new CodeWriter();
        $w->write("hello world");

        $this->space->save("GenClass", $w);

        $this->assertFileExists($this->dir."GenClass.php");
    }

    /** @test */
    function it_should_saves_the_code()
    {
        $w = new CodeWriter();
        $w->write("hello world");

        $this->space->save("GenClass", $w);

        $this->assertEquals(
            "hello world", file_get_contents($this->dir."GenClass.php"));
    }

    /** @test */
    function it_should_clears_the_code()
    {
        $w = new CodeWriter();
        $w->write("hello world");

        $this->space->save("GenClass", $w);
        $this->space->clear();

        $this->assertFileNotExists($this->dir."GenClass.php");
    }
} 
