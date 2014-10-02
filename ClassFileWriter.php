<?php
namespace Plum\Gen;

use Plum\Gen\ClassWriter\ClassSignatureWriter;
use Plum\Gen\ClassWriter\ClassWriter;
use Plum\Gen\ClassWriter\MethodWriter;
use Plum\Gen\ClassWriter\NamespaceWriter;
use Plum\Gen\ClassWriter\PropertyWriter;
use Plum\Gen\ClassWriter\UseWriter;
use Plum\Gen\ClassWriter\ValueWriter;

class ClassFileWriter implements
    NamespaceWriter, UseWriter
{
    private $writer;

    public function __construct(CodeWriter $writer)
    {
        $this->writer = $writer;
        $writer->writeln("<?php ");
    }

    /**
     * {@inheritdoc}
     */
    public function inNamespace($namespace)
    {
        $this->writer->write("namespace ", $namespace, ";");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function useType($fullyQualifiedName, $alias = null)
    {
        $this->writer->write("use ", $fullyQualifiedName);
        if ($alias)
            $this->writer->write(" as ", $alias);
        $this->writer->writeln(";");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function beginClass($modifier, $class)
    {
        if (($modifier & \ReflectionClass::IS_EXPLICIT_ABSTRACT)
            ||
            ($modifier & \ReflectionClass::IS_IMPLICIT_ABSTRACT)) {
            $this->writer->write("abstract ");
        } else if ($modifier & \ReflectionClass::IS_FINAL) {
            $this->writer->write("final ");
        }

        $this->writer->write("class ", $class);

        return new ClassWriter($this->writer);
    }
}
