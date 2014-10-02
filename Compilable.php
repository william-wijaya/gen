<?php
namespace Plum\Gen;

/**
 * Represents a compilable element
 */
interface Compilable
{
    /**
     * Compiles the element
     *
     * @param CodeWriter $writer
     */
    public function compile(CodeWriter $writer);
} 
