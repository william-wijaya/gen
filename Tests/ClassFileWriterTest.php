<?php
namespace Plum\Gen\Tests;

use Plum\Gen\ClassFileWriter;
use Plum\Gen\ClassWriter;
use Plum\Gen\CodeWriter;

class ClassFileWriterTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_writes_empty_class()
    {
        $w = new CodeWriter("\t");
        $cls = new ClassFileWriter($w);

        $cls->beginClass(null, "MyClass")
            ->endClass();

        $this->assertContains("class MyClass
{
\t
}
", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_namespace()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->inNamespace("test");

        $this->assertContains("namespace test;", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_use_import()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->useType("stdClass", "std");

        $this->assertContains("use stdClass as std;".PHP_EOL, $w->bufferedCode());
    }

    /** @test @dataProvider provideClassModifiers */
    function it_should_writes_class_modifiers($modBit, $modName)
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass($modBit, "MyClass");

        $this->assertContains($modName."class MyClass", $w->bufferedCode());
    }

    function provideClassModifiers()
    {
        return [
            [null, ""],
            [\ReflectionClass::IS_FINAL, "final "],
            [\ReflectionClass::IS_EXPLICIT_ABSTRACT, "abstract "],
            [\ReflectionClass::IS_IMPLICIT_ABSTRACT, "abstract "]
        ];
    }

    /** @test */
    function it_should_writes_class_extends()
    {
        $w = new CodeWriter("\t");
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->extend("stdClass")
            ->endClass();

        $this->assertContains(
            "class MyClass extends stdClass
{
\t
}
", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_implements()
    {
        $w = new CodeWriter("\t");
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->implement("Countable")
            ->endClass();

        $this->assertContains(
            "class MyClass implements Countable
{
\t
}
", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_const_with_constant_value()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->constant("MY_CONSTANT")->value("constant")
            ->endClass();

        $this->assertContains(
            'class MyClass
{
    const MY_CONSTANT = "constant";
}
', $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_const_with_expression_value()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->constant("MY_CONSTANT")->expression("1 + 1")
            ->endClass();

        $this->assertContains(
            "class MyClass
{
    const MY_CONSTANT = 1 + 1;
}
", $w->bufferedCode());
    }

    /** @test @dataProvider providePropertyModifier */
    function it_should_writes_property_modifier($modBit, $modName)
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->property($modBit, "myProp")
            ->endClass();

        $this->assertContains(
            'class MyClass
{
    '.$modName.' $myProp;
}
', $w->bufferedCode());
    }

    function providePropertyModifier()
    {
        return [
            [\ReflectionProperty::IS_PUBLIC, "public"],
            [\ReflectionProperty::IS_PROTECTED, "protected"],
            [\ReflectionProperty::IS_PRIVATE, "private"],

            [\ReflectionProperty::IS_STATIC, "static"],

            [\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_STATIC, "public static"],
            [\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_STATIC, "protected static"],
            [\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_STATIC, "private static"],

        ];
    }

    /** @test */
    function it_should_writes_property_with_constant_value()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->property(\ReflectionProperty::IS_PUBLIC, "myProp")
                ->value("myValue")
            ->endClass();

        $this->assertContains('class MyClass
{
    public $myProp = "myValue";
}
', $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_property_with_expression_value()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->property(\ReflectionProperty::IS_PUBLIC, "myProp")
            ->expression("1 + 1")
            ->endClass();

        $this->assertContains('class MyClass
{
    public $myProp = 1 + 1;
}
', $w->bufferedCode());
    }

    /** @test @dataProvider provideMethodModifiers */
    function it_should_writes_method_modifier($modBit, $modName)
    {
        $w = new CodeWriter("\t");
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->method($modBit, "myMethod")
            ->body(function() {})
            ->endClass();

        $this->assertContains(
            "class MyClass
{
\t{$modName} function myMethod()
\t{
\t\t
\t}
\t
}
", $w->bufferedCode());

    }

    function provideMethodModifiers()
    {
        return [
            [\ReflectionMethod::IS_PUBLIC, "public"],
            [\ReflectionMethod::IS_PROTECTED, "protected"],
            [\ReflectionMethod::IS_PRIVATE, "private"],

            [\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC, "public static"],
            [\ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_STATIC, "protected static"],
            [\ReflectionMethod::IS_PRIVATE | \ReflectionMethod::IS_STATIC, "private static"],

            [\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_FINAL, "public final"],
            [\ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_FINAL, "protected final"],
            [\ReflectionMethod::IS_PRIVATE | \ReflectionMethod::IS_FINAL, "private final"],

            [\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_ABSTRACT, "public abstract"],
            [\ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_ABSTRACT, "protected abstract"],
            [\ReflectionMethod::IS_PRIVATE | \ReflectionMethod::IS_ABSTRACT, "private abstract"],

            [\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_FINAL | \ReflectionMethod::IS_STATIC, "public static final"],
            [\ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_FINAL | \ReflectionMethod::IS_STATIC, "protected static final"],
            [\ReflectionMethod::IS_PRIVATE | \ReflectionMethod::IS_FINAL | \ReflectionMethod::IS_STATIC, "private static final"],
        ];
    }

    /** @test */
    function it_should_writes_method_body()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->method(null, "myMethod")
            ->body(function(CodeWriter $w) {
                $w->write("return 1;");
            })
            ->endClass();

        $this->assertContains("class MyClass
{
    function myMethod()
    {
        return 1;
    }
", $w->bufferedCode());
    }

    /** @test */
    function it_should_writes_method_parameter()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->method(null, "myMethod")
                ->parameter("param")
                ->body(function(CodeWriter $w) {
                    $w->write("return 1;");
                })
            ->endClass();

        $this->assertContains('class MyClass
{
    function myMethod($param)
    {
        return 1;
    }
', $w->bufferedCode());

    }

    /** @test */
    function it_should_writes_use_trait()
    {
        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->beginClass(null, "MyClass")
            ->useTrait("MyTrait")
            ->endClass();

        $this->assertContains('class MyClass
{
    use MyTrait;', $w->bufferedCode());
    }
} 
