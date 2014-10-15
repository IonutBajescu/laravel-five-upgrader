<?php namespace Ionut\LaravelFiveUpgrader\Annotations;


use Illuminate\Support\Collection;

class AnnotationsCollection extends Collection {

    public function generate(){
        $generated = call_user_func_array('AnnotationGenerator::make', func_get_args());
        $this->prepend($generated);
    }
} 