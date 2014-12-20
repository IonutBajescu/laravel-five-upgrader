<?php namespace Ionut\LaravelFiveUpgrader\Instructions;


use Illuminate\Support\Facades\Route;
use Ionut\LaravelFiveUpgrader\Annotations\AnnotationsCollection;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\PrettyPrinter\Standard;

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
		$this->parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
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

        // a mai ramas problema ca la $LINES_BEHIND practic conteaza doar daca metoda e sub cea afectata, deci trebuie sa verific cumva pozitia in fisier

        foreach($routesAnnotations as $location => $annotationsCollection){
            list($controller, $methodName) = explode('@', $location);

			$reflected = new \ReflectionClass($controller);

			$parsed = $this->setNewComments($reflected, $methodName, $annotationsCollection);

			$prettyPrinter = new Standard;
			$content = $prettyPrinter->prettyPrintFile($parsed);
            $this->files->put($reflected->getFileName(), $content);
        }
    }

    /**
     * @param $route
     * @return AnnotationsCollection
     */
    public function getRouteAnnotations(\Illuminate\Routing\Route $route)
    {
        $options = [];
        if ($route->getName()) {
            $options['as'] = $route->getName();
        }

        $annotations = new AnnotationsCollection();

        $method = $this->getRouteVerb($route);
        $annotations->append($method, $route->getPath(), $options);

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

            if(!isset($route->getAction()['controller'])){
                continue;
            }

            $annotations = $this->getRouteAnnotations($route);
            $routesAnnotations[ $route->getAction()['controller'] ] = $annotations;
        }

        return $routesAnnotations;
    }

    /**
     * @param \Illuminate\Routing\Route $route
     * @return string
     */
    protected function getRouteVerb(\Illuminate\Routing\Route $route)
    {
        if ($route->methods() == ['GET', 'HEAD']) {
            $method = 'Get';
        }
        if ($route->methods() == ['POST']) {
            $method = 'Post';
        }
        if ($route->methods() == ['PUT']) {
            $method = 'Put';
        }
        if ($route->methods() == ['DELETE']) {
            $method = 'Delete';

            return $method;
        }

        return $method;
    }

	/**
	 * @param $reflected
	 * @param $methodName
	 * @param $annotationsCollection
	 * @return \PhpParser\Node[]
	 */
	public function setNewComments($reflected, $methodName, $annotationsCollection)
	{
		$initial_content = $this->files->read($reflected->getFileName());
		$parsed = $this->parser->parse($initial_content);
		foreach ($parsed as $class) {
			if ($class instanceOf Class_) {
				foreach ($class->getMethods() as $classMethod) {
					if ($classMethod->name == $methodName) {
						$annotationsCollection = $annotationsCollection->setBase($classMethod->getDocComment()->getText());
						$classMethod->setAttribute('comments', [$annotationsCollection->parsable()]);

						break;
					}
				}

				break;
			}
		}

		return $parsed;
	}
}
