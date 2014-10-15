<?php namespace Ionut\LaravelFiveUpgrader;


/**
 * Class Upgrader
 *
 * @package Ionut\LaravelFiveUpgrader
 */
class Upgrader {
    /**
     * @var array
     */
    protected $upgraders = [
        Instructions\Routes::class
    ];

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
     * Boot target application, register autoload, boot laravel.
     */
    public function bootTargetApplication(){
        require $this->path.'/vendor/autoload.php';
        require $this->path.'/bootstrap/start.php';

    }

    /**
     * Upgrade Laravel 4 application to Laravel 5
     * using the $this->upgraders upgraders.
     */
    public function upgrade(){
        $this->bootTargetApplication();

        foreach($this->upgraders as $upgraderClass){
            /** @var Instructions\UpgraderInterface $instruction */
            $instruction = new $upgraderClass($this->path);
            $instruction->upgrade();
        }
    }
} 