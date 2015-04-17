<?php namespace Krumer\Test\Tools\Utils;

use ReflectionClass;

class AnnotationReader
{
    /**
     * The object to reflect into.
     *
     * @var object
     */
    protected $reference;

    /**
     * Create a new AnnotationReader instance.
     *
     * @param mixed $reference
     */
    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Get method names for the referenced object
     * which contain the given annotation.
     *
     * @param  string $annotation
     * @return array
     */
    public function methodsHaving($annotation)
    {
        $methods = [];

        foreach ($this->reflectedMethods($this->reference) as $method)
        {
            if ($this->hasAnnotation($annotation, $method))
            {
                $methods[] = $method->getName();
            }
        }

        return $methods;
    }

    /**
     * Reflect into the given object and returns all methods of the class.
     *
     * @param  object $object
     * @return ReflectionMethod
     */
    protected function reflectedMethods($object)
    {
        return (new ReflectionClass($object))->getMethods();
    }

    /**
     * Search the docblock for the given annotation.
     *
     * @param  string            $annotation
     * @param  \ReflectionMethod $method
     * @return boolean
     */
    protected function hasAnnotation($annotation, \ReflectionMethod $method)
    {
        return String::contains($method->getDocComment(), "@{$annotation}");
    }

}