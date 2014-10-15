<?php


use Ionut\LaravelFiveUpgrader\Annotations\AnnotationsCollection;

class AnnotationsCollectionTest extends PHPUnit_Framework_TestCase {

    function testBasicUsage()
    {
        $collection = new AnnotationsCollection();
        $collection->append('Middleware', 'SomeMiddleware');
        $collection->append('Get', '/some/location', ['as' => 'named']);

        $compiled = <<<COMPILED
    /**
     * @Get("/some/location", as="named")
     * @Middleware("SomeMiddleware")
     */
COMPILED;

        $this->assertEquals($collection->compile(), $compiled);
    }
}
 