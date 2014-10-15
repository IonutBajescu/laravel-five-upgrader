<?php namespace Ionut\LaravelFiveUpgrader\Annotations;


use Illuminate\Support\Collection;

/**
 * Class AnnotationsCollection
 *
 * @package Ionut\LaravelFiveUpgrader\Annotations
 */
class AnnotationsCollection extends Collection {

    /**
     * @param       $name
     * @param       $value
     * @param array $options
     * @return string
     */
    public function append(){
        $generated = call_user_func_array('AnnotationGenerator::make', func_get_args());
        $this->prepend($generated);
        return $generated;
    }
} 