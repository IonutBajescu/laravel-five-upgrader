<?php namespace Ionut\LaravelFiveUpgrader;


class Upgrader {
    protected $upgraders = [
        Instructions\Routes::class
    ];

    protected $path;

    function __construct($path)
    {
        $this->path = $path;
    }

    public function bootTargetApplication(){
        require $this->path.'/vendor/autoload.php';
        require $this->path.'/bootstrap/start.php';

    }

    public function upgrade(){
        $this->bootTargetApplication();

        foreach($this->upgraders as $upgraderClass){
            /** @var Instructions\UpgraderInterface $instruction */
            $instruction = new $upgraderClass($this->path);
            $instruction->upgrade();
        }
    }
} 