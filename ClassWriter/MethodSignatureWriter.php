<?php
namespace Plum\Gen\ClassWriter;

interface MethodSignatureWriter
    extends MethodBodyWriter
{
    /**
     * @param string $name
     * @param string $type
     * @param mixed $defaultValue
     *
     * @return MethodSignatureWriter
     */
    public function parameter($name, $type = null, $defaultValue = null);
} 
