<?php
namespace Plum\Gen\ClassWriter;

interface NamespaceWriter
{
    /**
     * @param string $namespace;
     *
     * @return UseWriter
     */
    public function inNamespace($namespace);
} 
