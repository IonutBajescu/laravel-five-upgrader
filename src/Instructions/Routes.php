<?php namespace Ionut\LaravelFiveUpgrader\Instructions;


use Illuminate\Support\Facades\Route;

class Routes implements UpgraderInterface {

    protected $filename = 'app/routes.php';

    protected $path;

    function __construct($path)
    {
        $this->path = $path;
    }


    public function routes(){
        $router = new \Illuminate\Routing\Router(new \Illuminate\Events\Dispatcher);
        Route::swap($router);

        require $this->getRoutesFile();

        return $router->getRoutes();
    }

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

                $annotation = 'Get("'.$route->getPath().'"';
                foreach($options as $k => $v){
                    $annotation .= ', '.$k.'={"'.implode('","', $v).'"}';
                }
                $annotation .= ')';
                var_dump($annotation);

                $methodAnnotations[] = $annotation;
            }
        }
    }

    private function getRoutesFile()
    {
        return $this->path.'/'.$this->filename;
    }
}