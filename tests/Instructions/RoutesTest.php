<?php

use Ionut\LaravelFiveUpgrader\Filesystem\TestingAdapter;
use Ionut\LaravelFiveUpgrader\Instructions\Routes;
use Ionut\LaravelFiveUpgrader\Upgrader;
use League\Flysystem\Filesystem;
use Mockery as m;

class RoutesTest extends PHPUnit_Framework_TestCase {

    public function testReplacing(){
        $files = $this->mockFileSystem([
            'app/routes.php' => __DIR__.'/../Fixtures/Instructions/Routes/routes.php'
        ]);

        $instruction = new Routes($files);
        $annotations = $instruction->getRoutesAnnotations();
        $compiled = <<<COMPILED
/**
     * @Get("alt/moloz", as="moloz")
     */
COMPILED;
        $this->assertEquals($compiled, $annotations['AuthController@test']->compile());
    }

    public function testControllerInjectionWithAlreadyComments()
    {
        $files = $this->mockFileSystem([
            'app/routes.php' => __DIR__.'/../Fixtures/Instructions/Routes/routes-2.php',
            dirname(__DIR__).'/Fixtures/Instructions/Routes/TestController.php' => dirname(__DIR__).'/Fixtures/Instructions/Routes/TestController.php'
        ]);

        $testedController = dirname(__DIR__).'/Fixtures/Instructions/Routes/TestController.php';
        require  $testedController;
        $instruction = new Routes($files);
        $instruction->upgrade();

		file_put_contents('parsed', $files->read($testedController));
        $this->assertSame(
            file_get_contents(__DIR__.'/../Fixtures/Instructions/Routes/EXPECTED-TestController.php'),
            $files->read($testedController)
        );
    }

    public function setUp()
    {
        $upgrader = new Upgrader('learn');
        $upgrader->bootTargetApplication();
    }


    /**
     * @param  $files
     * @return Filesystem
     */
    protected function mockFileSystem($files)
    {
        return new Filesystem(new TestingAdapter($files));
    }
}
 