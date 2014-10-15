<?php namespace Ionut\LaravelFiveUpgrader;


class AnnotationGenerator {

    static public function make($name, $value, $options = []){
        $annotation = $name.'("'.$value.'"';
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