<?php namespace Krumer\Test\Tools;

use Krumer\Test\Tools\Utils\AnnotationReader;

trait AnnotationsTrait {

    /**
     * The annotation reader instance.
     *
     * @var AnnotationReader
     */
    protected $annotationsReader;

    /**
     * Prepare the test for PHPUnit.
     *
     * @return  void
     */
    public function setUp()
    {
        parent::setUp();

        $methods = $this->methodsHavingAnnotation('setUp');
        $this->callMethods($methods);
    }

    /**
     * Clean up after for PHPUnit.
     *
     * @return void
     */
    public function tearDown()
    {
        $methods = $this->methodsHavingAnnotation('tearDown');
        $this->callMethods($methods);

        parent::tearDown();
    }

    /**
     * Get the methods containing the given annotations.
     *
     * @param $annotation
     * @return mixed
     */
    public function methodsHavingAnnotation($annotation)
    {
        if (! isset($this->annotations))
        {
            $this->annotations = [];
        }

        if( ! isset($this->annotations[$annotation]))
        {
            $reader = AnnotationReader($this);

            $this->annotations[$annotation] = $reader->methodsHaving($annotation);
        }

        return $this->annotations[$annotation];
    }

    /**
     * Trigger all provided methods on the current object.
     *
     * @param  array $methods
     * @return void
     */
    protected function callMethods(array $methods)
    {
        foreach ($methods as $method)
        {
            call_user_func([$this, $method]);
        }
    }

}