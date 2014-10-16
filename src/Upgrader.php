<?php namespace Ionut\LaravelFiveUpgrader;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

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
     * @var Filesystem
     */
    private $mockedFileSystem;

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

        $filesystem = $this->getFileSystem();

        foreach($this->upgraders as $upgraderClass){
            /** @var Instructions\UpgraderInterface $instruction */
            $instruction = new $upgraderClass($filesystem);
            $instruction->upgrade();
        }
    }

    /**
     * @return Filesystem
     */
    protected function getFileSystem()
    {
        if( ! is_null($this->mockedFileSystem)){
            return $this->mockedFileSystem;
        }

        $filesystem = new Filesystem(new Adapter($this->path));

        return $filesystem;
    }

    /**
     * @param Filesystem $filesSystem
     */
    protected function mockFileSystem($filesSystem)
    {
        $this->mockedFileSystem = $filesSystem;
    }

} 