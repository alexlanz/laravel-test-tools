<?php namespace Krumer\Test\Tools;

use Krumer\Test\Tools\Utils\AnnotationReader;

trait AnnotationsTrait {

    /**
     * The annotation reader instance.
     *
     * @var AnnotationReader
     */
    protected $annotations;

    /**
     * Prepare the test for PHPUnit.
     *
     * @return  void
     */
    public function setUp()
    {
        parent::setUp();

        $methods = $this->getAnnotations()->methodsHaving('setUp');
        $this->callMethods($methods);
    }

    /**
     * Clean up after for PHPUnit.
     *
     * @return void
     */
    public function tearDown()
    {
        $methods = $this->getAnnotations()->methodsHaving('tearDown');
        $this->callMethods($methods);

        parent::tearDown();
    }

    /**
     * Get the annotation reader instance.
     *
     * @return AnnotationReader
     */
    public function getAnnotations()
    {
        if (! $this->annotations)
        {
            $this->annotations = new AnnotationReader($this);
        }

        return $this->annotations;
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