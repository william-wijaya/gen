<?php
namespace Plum\Gen\ClassWriter;

interface ExtendsWriter
{
    /**
     * @param string $super
     * @return ImplementsWriter
     */
    public function extend($super);
} 
