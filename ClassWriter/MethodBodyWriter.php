<?php
namespace Plum\Gen\ClassWriter;

interface MethodBodyWriter
{
    /**
     * @param \Closure $body
     * @return ClassBodyWriter
     */
    public function body(\Closure $body);
} 
