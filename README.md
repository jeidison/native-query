

# Installation

```bash
$ composer require jeidison/native-query
```

# Publish Settings

```bash
$ php artisan vendor:publish --provider="Jeidison\NativeQuery\Providers\NativeQueryServiceProvider"
```
This will add the nativequery.php file in your config directory with the following contents:

```php
return [
    'path-sql' => database_path('native-query'),
    'type' => Jeidison\NativeQuery\Enums\FileType::PHP,
];
```
And this will add the native-query.xml file in your database directory with the following contents:

# SQL in file XML

```xml
<query name="findTab1">
    SELECT * FROM TAB1 WHERE PAR1 = ?
</query>
```

And this will add the native-query.php file in your database directory with the following contents:
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
ModelX::nativeQuery('findTab1')->param('par1', 'value1')->param('par2', 'value2')->exec();

ModelX::nativeQuery('findTab1')->param(['par1' => 'value1'])->exec();

ModelX::nativeQuery('findTab1')->param(['par1' => 'value1'])->->debug();

NativeQuery::query('findTab1')
            ->queryFile('/path/file-with-queries')
            ->param('par1', 'value1')
            ->exec();
```
