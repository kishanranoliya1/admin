<?php

namespace App\Lib\Swedivine;

class Swedivine {

    protected $path = null;
    protected $query = null;
    protected $output = [];
    protected $status = null;
    protected $hasOutput = false;
    protected $maskPath = true;
    protected $lastQuery = null;

    /**
     * @param $query string
     * @return $this
     */
    public function query($query)
    {
        $libPath = '/home/astro3divine/public_html/app/Lib/Swedivine/resources/sweph';
        putenv("PATH=$libPath");
        $cmdpath = "/home/astro3divine/public_html/app/Lib/Swedivine/resources/swe_lib/src/";
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmdpath = base_path('app\Lib\Swedivine\resources\\');
            $libPath = base_path('app\Lib\Swedivine\resources\sweph');
            putenv("PATH=$libPath");
        }

        is_array($query) and $query = $this->compile($query);
        $this->query = $cmdpath.'swetest -edir'.$libPath.' '.$query;

        return $this;
    }

    /**
     * @param $arr
     * @return string
     */
    public function compile($arr)
    {
        $options = [];
        foreach ($arr as $key => $value) {
            $options[] = is_int($key) ? '-'.$value : '-'.$key.$value;
        }

        return implode(' ', $options);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     * @return $this
     * @throws SwedivineException
     */
    public function setPath($path)
    {
        if (is_dir($path) and is_file($path.'swetest')) {
            $this->path = $path;
        } else {
            throw new SwedivineException('Invalid path!');
        }

        return $this;
    }

    /**
     * @return $this
     * @throws SwedivineException
     */
    public function execute()
    {
        if ($this->query === null) {
            throw new SwedivineException('No query!');
        }
        exec($this->query, $output, $status);
        
        $this->output = $output;
        $this->status = $status;
        if ($this->maskPath) {
            $this->maskPath($this->output[0]);
            $this->maskPath($this->query);
        }

        $this->hasOutput = true;
        $this->lastQuery = $this->query;

        return $this;
    }

    /**
     * @param $path
     */
    protected function maskPath(&$path)
    {
        $path = str_replace($this->path, '***-***', $path);
    }

    /**
     * @param $needMask
     * @return $this
     */
    public function setMaskPath($needMask)
    {
        $this->maskPath = (bool) $needMask;

        return $this;
    }

    /**
     * @return array
     * @throws SwedivineException
     */
    public function response()
    {
        if ($this->hasOutput === false) {
            throw new SwedivineException('Need `execute()` before call this method!');
        }

        return [
            'status' => $this->getStatus(),
            'output' => $this->getOutput(),
        ];
    }

    /**
     * @return int
     * @throws SwedivineException
     */
    public function getStatus()
    {
        if ($this->hasOutput === false) {
            throw new SwedivineException('Need `execute()` before call this method!');
        }

        return $this->status;
    }

    /**
     * @return array
     * @throws SwedivineException
     */
    public function getOutput()
    {
        if ($this->hasOutput === false) {
            throw new SwedivineException('Need `execute()` before call this method!');
        }

        return $this->output;
    }

    /**
     * @return string|null
     */
    public function getLastQuery()
    {
        return $this->lastQuery;
    }

}