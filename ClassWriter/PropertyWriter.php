<?php
namespace Plum\Gen\ClassWriter;

use Plum\Gen\CodeWriter;

class PropertyWriter implements OptionalValueWriter
{
    private $class;
    private $writer;

    public function __construct(ClassWriter $class, CodeWriter $writer)
    {
        $this->class = $class;
        $this->writer = $writer;
    }

    /**
     * {@inheritdoc}
     */
    public function property($modifier, $name)
    {
        $this->writer->writeln(";");

        return $this->class->property($modifier, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function constant($name)
    {
        $this->writer->writeln(";");
        $this->writer->nl();

        return $this->class->constant($name);
    }

    /**
     * {@inheritdoc}
     */
    public function method($modifier, $name)
    {
        $this->writer->writeln(";");
        $this->writer->nl();

        return $this->class->method($modifier, $name);
    }

    public function endClass()
    {
        $this->writer->write(";");

        $this->class->endClass();
    }

    /**
     * {@inheritdoc}
     */
    public function value($val)
    {
        $this->writer->write(" = ");
        $this->writer->literal($val);
        $this->writer->write(";");

        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function expression($expr)
    {
        $this->writer->write(" = ");
        $this->writer->write($expr, ";");

        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function useTrait($name)
    {
        $this->writer->writeln(";");
        $this->writer->nl();

        return $this->class->useTrait($name);
    }
}
