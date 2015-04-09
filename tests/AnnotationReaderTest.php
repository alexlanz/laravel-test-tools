<?php

use Krumer\Test\Tools\Utils\AnnotationReader;

class Annotations {

    /**
     * @setUp
     */
    public function exampleSetUp()
    {

    }

    /**
     * @tearDown
     */
    public function exampleTearDown()
    {

    }

}

class AnnotationReaderTest extends PHPUnit_Framework_TestCase  {

    public function test_method_annotations()
    {
        $object = new Annotations();

        $reader = new AnnotationReader($object);
        $methods = $reader->methodsHaving('setUp');

        $this->assertCount(1, $methods);
        $this->assertContains('exampleSetUp', $methods);
    }

}