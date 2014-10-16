<?php namespace Ionut\LaravelFiveUpgrader\Instructions;


use Illuminate\Support\Facades\Route;
use Ionut\LaravelFiveUpgrader\Annotations\AnnotationsCollection;

/**
 * Class Routes
 *
 * @package Ionut\LaravelFiveUpgrader\Instructions
 */
class Routes implements UpgraderInterface
{

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $files;

    /**
     * @param \League\Flysystem\Filesystem $files
     */
    function __construct(\League\Flysystem\Filesystem $files)
    {
        $this->files = $files;
    }


    /**
     * @return \Illuminate\Routing\RouteCollection
     */
    public function routes()
    {
        $router = new \Illuminate\Routing\Router(new \Illuminate\Events\Dispatcher);
        Route::swap($router);

        $routesCode = $this->files->read('app/routes.php');
        $routesCode = str_replace(['<?php', '?>'], '', $routesCode);
        eval($routesCode);

        return $router->getRoutes();
    }

    /**
     * Transfer all controller routes from routes.php to
     * new annotations, directly in controller methods.
     */
    public function upgrade()
    {
        $routesAnnotations = $this->getRoutesAnnotations();

        /**
         * @todo Write annotation in file
         *       (test first this shit)
         */
    }

    /**
     * @param $route
     * @return AnnotationsCollection
     */
    public function getRouteAnnotations($route)
    {
        $options = [];
        if ($route->getName()) {
            $options['as'] = $route->getName();
        }

        $annotations = new AnnotationsCollection();
        $annotations->append('Get', $route->getPath(), $options);

        return $annotations;
    }

    /**
     * Generate routes annotations with routes parsed
     * from app/routes.php file. Is a good night.
     *
     * @return array.
     */
    public function getRoutesAnnotations()
    {
        $routesAnnotations = [];
        foreach ($this->routes() as $route) {
            /** @var \Illuminate\Routing\Route $route */

            if ($route->getActionName() == 'Closure') {
                continue;
            }

            if ($route->methods() == ['GET', 'HEAD']) {
                $annotations = $this->getRouteAnnotations($route);

                $routesAnnotations[ $route->getActionName() ] = $annotations;
            }
        }

        return $routesAnnotations;
    }
}