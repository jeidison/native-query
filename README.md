# Install

# install with composer

```bash
$ composer require jeidison/native-query
```

# Publish Settings

```bash
$ php artisan vendor:publish --provider="Jeidison\NativeQuery\Providers\NativeQueryServiceProvider"
```

```php
return [
    'path-sql' => database_path('native-query'),
    'type' => Jeidison\NativeQuery\Enums\FileType::PHP,
];
```

# SQL in file XML

```xml
<query name="findTab1">
    SELECT * FROM TAB1 WHERE PAR1 = ?
</query>
```
# SQL in file PHP

```php
CONST findTab1 = "
SELECT * FROM TAB1 WHERE PAR1 = :par1
";
```
# Add Trait in model

```php
...

class ModelX extends Model
{
    use HasNativeQuery;
    
    protected $queryFile = '/path/file-with-queries';

    ...
}
```

# Executing SQL

```php
ModelX::nativeQuery('findTab1')->param('par1', 'value1')->exec();
ModelX::nativeQuery('findTab1')->param(['par1' => 'value1'])->exec();
ModelX::nativeQuery('findTab1')->->debug();
```
