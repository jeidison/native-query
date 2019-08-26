<?php

namespace Jeidison\NativeQuery\NativeQuery;

class NativeQueryParameters {

    private $queryFile;
    private $queryName;
    private $class;

    /**
     * @return mixed
     */
    public function getQueryFile()
    {
        return $this->queryFile;
    }

    /**
     * @param mixed $queryFile
     * @return NativeQueryParameters
     */
    public function setQueryFile($queryFile)
    {
        $this->queryFile = $queryFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQueryName()
    {
        return $this->queryName;
    }

    /**
     * @param mixed $queryName
     * @return NativeQueryParameters
     */
    public function setQueryName($queryName)
    {
        $this->queryName = $queryName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return NativeQueryParameters
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

}

