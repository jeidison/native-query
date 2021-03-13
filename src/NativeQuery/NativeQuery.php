<?php

namespace Jeidison\NativeQuery\NativeQuery;

use DOMDocument;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jeidison\NativeQuery\Enums\FileType;

class NativeQuery
{

    /**
     * @var NativeQueryParameters
     */
    private $parameters;
    private $bindings = [];
    private $debug = false;
    private $load = [];
    private $appends = [];

    public function __construct(NativeQueryParameters $parameters)
    {
        $this->parameters = $parameters;
    }

    public static function query($queryName): NativeQuery
    {
        $parameters = new NativeQueryParameters();
        $parameters->setQueryName($queryName);
        return new static($parameters);
    }

    public function queryFile($queryFile)
    {
        $this->parameters->setQueryFile($queryFile);
        return $this;
    }

    public function param($param, $value = null)
    {
        if (!is_array($param)) {
            $param = [
                $param => $value
            ];
        }
        $this->bindings = array_merge($this->bindings, $param);
        return $this;
    }

    public function noClass()
    {
        return $this->toClass(null);
    }

    public function toClass($class)
    {
        $this->parameters->setClass($class);
        return $this;
    }

    public function load($load = [])
    {
        $this->load = $load;
        return $this;
    }

    public function appends($appends = [])
    {
        $this->appends = $appends;
        return $this;
    }

    public function debug()
    {
        $this->debug = true;
        return $this->exec();
    }
    
    public function first()
    {
        return $this->exec()->first();
    }

    public function exec()
    {
        $query = $this->getSqlAsString();

        foreach($this->bindings as $paramName => $values) {
            if (is_array($values) ) {
                $values = array_filter(array_map(function ($value) {
                    return is_numeric($value) ? $value : "'".$value."'";
                }, $values));

                $values = join(',', $values);
                $query = str_replace(":$paramName", $values, $query);
                unset($this->bindings[$paramName]);
            }
        }

        if ($this->debug) {
            $query = Str::replaceArray('?', $this->bindings, $query);
            foreach($this->bindings as $key => $binding) {
                $value = is_numeric($binding) ? $binding : "'".$binding."'";
                $query = str_replace(":$key", $value, $query);
            }
            return $query;
        }

        $results = DB::select(DB::raw($query), $this->bindings);
        if ($this->parameters->getClass() != null) {
            $results = $this->toObject($results);
        }
        return $results;
    }

    private function getSqlAsString()
    {
        $settings = config('nativequery');
        $path = $settings['base_path'] . $this->parameters->getQueryFile();
        $name = $this->parameters->getQueryName();
        if ($settings['type'] == FileType::XML) {
            return $this->getSqlFromXml($path, $name);
        }
        return $this->getSqlFromPhp($path, $name);
    }

    private function getSqlFromPhp($path, $name)
    {
        $path = $path . '.php';
        $this->checkFileExists($path);
        include_once($path);
        throw_if(!defined($name), new Exception("Const $name with query undefined"));
        return constant($name);
    }

    private function getSqlFromXml($path, $name)
    {
        $xmlDoc = new DOMDocument();
        $path = $path . '.xml';
        $this->checkFileExists($path);
        $xmlDoc->load($path);
        $searchNode = $xmlDoc->getElementsByTagName("query");
        foreach ($searchNode as $node) {
            $queryName = $node->getAttribute('name');
            if ($queryName != $name) {
                continue;
            }
            return $node->nodeValue;
        }
    }

    private function toObject($results)
    {
//        if (count($results) == 1) {
//            return collect()->push($this->hydrateObject($results[0]));
//        }
        $listObj = collect();
        foreach ($results as $result) {
            $object = $this->hydrateObject($result);
            $listObj->push($object);
        }
        return $listObj;
    }

    private function hydrateObject($data)
    {
        $resultClass = $this->parameters->getClass();
        $instance = new $resultClass();
        $fill = array('*');
        $values = ($fill) ? (array)$data : array_intersect_key((array)$data, array_flip($fill));
        $instance->setRawAttributes($values, true);
        $instance->exists = false;
        $instance->load($this->load);
        $instance->setAppends($this->appends);
        return $instance;
    }

    private function checkFileExists($path)
    {
        throw_if(!file_exists($path), new Exception("File $path not exists"));
    }
}
