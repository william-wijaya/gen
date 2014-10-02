<?php
namespace Plum\Gen\ClassWriter;

interface ValueWriter
{
    /**
     * @param mixed $val
     *
     * @return ClassBodyWriter
     */
    public function value($val);

    /**
     * @param string $expr
     *
     * @return ClassBodyWriter
     */
    public function expression($expr);
} 
