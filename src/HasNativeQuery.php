<?php

namespace Jeidison\NativeQuery;

use Exception;
use Jeidison\NativeQuery\NativeQuery\NativeQuery;
use Jeidison\NativeQuery\NativeQuery\NativeQueryParameters;

trait HasNativeQuery
{
    protected $nativeQuery;

    public static function nativeQuery($queryName): NativeQuery
    {
        throw_if(!property_exists(static::class, 'queryFile'), new Exception("Property 'queryFile' undefined."));
        $self = new static;
        $parameters = new NativeQueryParameters();
        $parameters->setQueryFile($self->queryFile);
        $parameters->setQueryName($queryName);
        $parameters->setClass(static::class);
        $self->nativeQuery = new NativeQuery($parameters);
        return $self->nativeQuery;
    }

}
