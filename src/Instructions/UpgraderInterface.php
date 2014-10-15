<?php


namespace Ionut\LaravelFiveUpgrader\Instructions;


interface UpgraderInterface {

    function __construct($path);

    public function upgrade();
} 