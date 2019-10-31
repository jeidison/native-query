<?php

namespace Tests\Models;

use Jeidison\NativeQuery\HasNativeQuery;
use Illuminate\Database\Eloquent\Model;

class ModelTest extends Model
{
    use HasNativeQuery;

    protected $queryFile = __DIR__.'/../database/native-query';

}
