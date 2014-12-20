<?php namespace Ionut\LaravelFiveUpgrader\Annotations;


use Illuminate\Support\Collection;
use PhpParser\Comment\Doc;

/**
 * Class AnnotationsCollection
 *
 * @package Ionut\LaravelFiveUpgrader\Annotations
 */
class AnnotationsCollection extends Collection {

    const TAB = '    ';

    /**
     * @param       $name
     * @param       $value
     * @param array $options
     * @return string
     */
    public function append(){
        $generated = call_user_func_array(AnnotationGenerator::class.'::make', func_get_args());
        $this->prepend($generated);
        return $generated;
    }

	/**
	 * Return PHP-Parser parsable comments
	 */
	public function parsable(){
		return new Doc($this->compile());
	}

	public function setBase($base){
		$base = $this->prepareArrayBase($base);
		if(count($base) >= 1){
			$base[] = '';
		}

		$array = $this->toArray();
		foreach(array_reverse($base) as $doc){
			array_unshift($array, $doc);
		}


		return new static($array);
	}

    /**
     * @param string $base Initial docblock value, we will append to
     *                     that value our annotations.
     * @return string
     */
    public function compile($base = ''){
        $base = $this->prepareStringBase($base);
        $annotations = '';
        foreach($this as $annotation){
            $annotations .= self::TAB.' * '.$annotation.PHP_EOL;
        }

        return "/**\n{$base}{$annotations}".self::TAB." */";

    }

	/**
	 * @param $base
	 */
	private function prepareArrayBase($base)
	{
		if(!$base) return '';

		$base = preg_replace('#\s*/\\*\\*\s*#', '', $base);
		$base = preg_replace('#\s*\\*/\s*#', '', $base);
		$base = explode(PHP_EOL, $base);
		return array_map(function($v){
			return preg_replace('#\s*\*\s*#', '', $v);
		}, $base);
	}

    /**
     * @param $base
     */
    private function prepareStringBase($base)
    {
        if(!$base) return '';

        $base = preg_replace('#\s*/\\*\\*\s*#', '', $base);
        $base = preg_replace('#\s*\\*/\s*#', '', $base);
        return self::TAB.' '.rtrim($base)."\n".self::TAB." *\n";
    }
}