<?php
namespace Plum\Gen\ClassWriter;

interface ClassBodyWriter
{
    /**
     * @param string $name
     *
     * @return ClassBodyWriter
     */
    public function useTrait($name);

    /**
     * @param int $modifier
     * @param string $name
     *
     * @return OptionalValueWriter
     */
    public function property($modifier, $name);

    /**
     * @param string $name
     *
     * @return ValueWriter
     */
    public function constant($name);

    /**
     * @param int $modifier
     * @param string $name
     *
     * @return MethodSignatureWriter
     */
    public function method($modifier, $name);

    public function endClass();
} 
