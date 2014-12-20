<?php namespace Ionut\LaravelFiveUpgrader\Filesystem;


use League\Flysystem\Adapter\NullAdapter;

/**
 * Class TestingAdapter
 *
 * @package Ionut\LaravelFiveUpgrader\Filesystem
 */
class TestingAdapter extends NullAdapter {
    /**
     * @var
     */
    protected $files;

    /**
     * @param $files
     */
    function __construct($files)
    {
        foreach($files as $file => $source){
            $this->mock($file, $source);
        }
    }


    /**
     * @param $path
     * @param $sourceFile
     */
    public function mock($path, $sourceFile)
    {
        $this->write($path, file_get_contents($sourceFile));
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function read($path)
    {
        $path = ltrim($path, '/');
        $contents = $this->files[$path];
        return compact('contents', 'path');
    }

    /**
     * @param string $path
     * @param string $contents
     * @param null   $config
     * @return array|bool
     */
    public function write($path, $contents, $config = null)
    {
        $path = ltrim($path, '/');
        $this->files[$path] = $contents;
        return parent::write($path, $contents, $config);
    }

    /**
     * @param  string $path
     * @return bool
     */
    public function has($path)
   {
        return isset($this->files[$path]);
    }

    public function update($path, $contents, $config = null)
    {
        return call_user_func_array([$this, 'write'], func_get_args());
    }
}