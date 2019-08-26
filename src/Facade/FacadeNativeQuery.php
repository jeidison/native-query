<?php

namespace Jeidison\NativeQuery\Facade;

use Illuminate\Support\Facades\Facade;

class FacadeNativeQuery extends Facade
{

    protected static function getFacadeAccessor() {
        return 'NativeQuery';
    }

}