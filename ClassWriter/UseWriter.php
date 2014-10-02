<?php
namespace Plum\Gen\ClassWriter;

use Plum\Gen\ClassWriter;

interface UseWriter
{
    /**
     * @param string $fullyQualifiedName
     * @param string $alias
     * @return UseWriter
     */
    public function useType($fullyQualifiedName, $alias = null);

    /**
     * @param int $modifier
     * @param string $class
     *
     * @return ClassSignatureWriter
     */
    public function beginClass($modifier, $class);
} 
