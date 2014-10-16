<?php namespace Ionut\LaravelFiveUpgrader\Instructions;


/**
 * Interface UpgraderInterface
 *
 * @package Ionut\LaravelFiveUpgrader\Instructions
 */
interface UpgraderInterface {

    /**
     * @param \League\Flysystem\Filesystem $files
     */
    function __construct(\League\Flysystem\Filesystem $files);

    /**
     * @return mixed
     */
    public function upgrade();
} 