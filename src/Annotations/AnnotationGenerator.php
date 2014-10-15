<?php namespace Ionut\LaravelFiveUpgrader\Annotations;


/**
 * Class AnnotationGenerator
 *
 * @package Ionut\LaravelFiveUpgrader\Annotations
 */
class AnnotationGenerator {

    /**
     * @param       $name
     * @param       $value
     * @param array $options
     * @return string
     */
    static public function make($name, $value, $options = []){
        $annotation = '@'.$name.'("'.$value.'"';
        $annotation = self::appendOptions($options, $annotation);
        $annotation .= ')';

        return $annotation;
    }

    /**
     * @param $options
     * @param $annotation
     * @return string
     */
    public static function appendOptions($options, $annotation)
    {
        foreach ($options as $k => $v) {
            if (is_array($v)) {
                $annotation .= ', ' . $k . '={"' . implode('","', $v) . '"}';
            } else {
                $annotation .= ', ' . $k . '="' . $v . '"';
            }
        }

        return $annotation;
    }
} 