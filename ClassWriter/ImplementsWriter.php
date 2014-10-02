<?php
namespace Plum\Gen\ClassWriter;

interface ImplementsWriter extends ClassBodyWriter
{
    /**
     * @param string $interface
     * @param string,... $interfaces
     *
     * @return ClassBodyWriter
     */
    public function implement($interface, ...$interfaces);
} 

