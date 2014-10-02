<?php
namespace Plum\Gen\ClassWriter;

use Plum\Gen\CodeWriter;

class ClassWriter implements ClassSignatureWriter, ValueWriter
{
    private $writer;

    private $extended = false;

    public function __construct(CodeWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * {@inheritdoc}
     */
    public function property($modifier, $name)
    {
        if (!$this->extended) {
            $this->writer->nl();
            $this->writer->write("{");
            $this->writer->indent();
        }

        $this->extended = true;

        if ($modifier & \ReflectionProperty::IS_PRIVATE)
            $this->writer->write("private ");
        else if ($modifier & \ReflectionProperty::IS_PROTECTED)
            $this->writer->write("protected ");
        else if ($modifier & \ReflectionProperty::IS_PUBLIC)
            $this->writer->write("public ");

        if ($modifier & \ReflectionProperty::IS_STATIC)
            $this->writer->write("static ");

        $this->writer->write('$', $name);

        return new PropertyWriter($this, $this->writer);
    }

    /**
     * {@inheritdoc}
     */
    public function constant($name)
    {
        if (!$this->extended) {
            $this->writer->nl();
            $this->writer->write("{");
            $this->writer->indent();

            $this->extended = true;
        }

        $this->writer->write("const ", $name, " = ");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function method($modifier, $name)
    {
        if (!$this->extended) {
            $this->writer->nl();
            $this->writer->write("{");
            $this->writer->indent();

            $this->extended = true;
        }

        if ($modifier & \ReflectionMethod::IS_PUBLIC)
            $this->writer->write("public ");
        else if ($modifier & \ReflectionMethod::IS_PROTECTED)
            $this->writer->write("protected ");
        else if ($modifier & \ReflectionMethod::IS_PRIVATE)
            $this->writer->write("private ");

        if ($modifier & \ReflectionMethod::IS_STATIC)
            $this->writer->write("static ");

        if ($modifier & \ReflectionMethod::IS_ABSTRACT)
            $this->writer->write("abstract ");

        if ($modifier & \ReflectionMethod::IS_FINAL)
            $this->writer->write("final ");

        $this->writer->write("function ", $name, "(");

        return new MethodWriter($this, $this->writer);
    }

    /**
     * {@inheritdoc}
     */
    public function useTrait($name)
    {
        if (!$this->extended) {
            $this->writer->nl();
            $this->writer->write("{");
            $this->writer->indent();

            $this->extended = true;
        }

        $this->writer->writeln("use ", $name, ";");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function endClass()
    {
        if (!$this->extended) {
            $this->writer->nl();
            $this->writer->write("{");
            $this->writer->indent();
        }

        $this->writer->outdent();
        $this->writer->write("}");
        $this->writer->nl();
    }

    /**
     * {@inheritdoc}
     */
    public function extend($super)
    {
        $this->writer->write(" extends ", $super);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function implement($interface, ...$interfaces)
    {
        array_unshift($interfaces, $interface);

        $this->writer->write(" implements ", implode(", ", $interfaces));

        $this->writer->nl();
        $this->writer->write("{");
        $this->writer->indent();

        $this->extended = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function value($val)
    {
        $this->writer->literal($val);
        $this->writer->write(";");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expression($expr)
    {
        $this->writer->write($expr);
        $this->writer->write(";");

        return $this;
    }
}
