<?php namespace Ionut\LaravelFiveUpgrader\Instructions;


use Illuminate\Support\Facades\Route;
use Ionut\LaravelFiveUpgrader\Annotations\AnnotationGenerator;
use Ionut\LaravelFiveUpgrader\Annotations\AnnotationsCollection;

/**
 * Class Routes
 *
 * @package Ionut\LaravelFiveUpgrader\Instructions
 */
class Routes implements UpgraderInterface {

    /**
     * @var string
     */
    protected $filename = 'app/routes.php';

    /**
     * @var string
     */
    protected $path;

    /**
     * @param $path
     */
    function __construct($path)
    {
        $this->path = $path;
    }


    /**
     * @return \Illuminate\Routing\RouteCollection
     */
    public function routes(){
        $router = new \Illuminate\Routing\Router(new \Illuminate\Events\Dispatcher);
        Route::swap($router);

        require $this->getRoutesFile();

        return $router->getRoutes();
    }

    /**
     * Transfer all controller routes from routes.php to
     * new annotations, directly in controller methods.
     */
    public function upgrade(){
        foreach($this->routes() as $route){
            /** @var \Illuminate\Routing\Route $route */

            if($route->getActionName() == 'Closure') continue;

            $methodAnnotations = [];
            if($route->methods() == ['GET', 'HEAD']){
                $options = [];
                if($route->getName()){
                    $options['as'] = $route->getName();
                }

                $annotation = new AnnotationsCollection();
                $annotation->append('Get', $route->getPath(), $options);

                $methodAnnotations[$route->getActionName()] = $annotation;
            }
        }
    }

    /**
     * @return string
     */
    private function getRoutesFile()
    {
        return $this->path.'/'.$this->filename;
    }
}