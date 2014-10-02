<?php
namespace Plum\Gen\ClassWriter;

use Plum\Gen\CodeWriter;

class MethodWriter implements MethodSignatureWriter
{
    private $class;

    private $writer;

    private $paramCounter = 0;

    public function __construct(ClassWriter $class, CodeWriter $writer)
    {
        $this->class = $class;
        $this->writer = $writer;
    }

    /**
     * {@inheritdoc}
     */
    public function body(\Closure $body)
    {
        $this->writer->writeln(")");
        $this->writer->write("{");
        $this->writer->indent();

        $body($this->writer);

        $this->writer->outdent();
        $this->writer->writeln("}");

        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function parameter($name, $type = null, $defaultValue = null)
    {
        if ($this->paramCounter)
            $this->writer->write(", ");

        if ($type)
            $this->writer->write($type, " ");

        $this->writer->write('$', $name);

        if (func_num_args() === 3)
            $this->writer->literal($defaultValue);

        ++$this->paramCounter;

        return $this;
    }
}
