A class loader for php5.3+
==========================================

 * psr4 compatible
 * set_include_path="on" is required!

### Example 1

```php
$loader = new ClassLoader();
$loader
->setBasePath(__DIR__)
->addPath('src')
->addPath('tests');
```

### Example 2

```php
$filter = new Filters\WildcardFilter();
$filter->includeNamespace('Kir\\*');

$loader = new ClassLoader();
$loader
->addFilter($filter)
->setBasePath(__DIR__)
->addPath('src')
->addPath('tests');
```

### Example 3

```php
$classes = array(
	'Kir\\Some\\Namespaced\\Class1' => 'src/Kir/Some/Namespaced/Class1',
	'Kir\\Some\\Namespaced\\Class2' => 'src/Kir/Some/Namespaced/Class2',
);
$provider = new Filters\WildcardFilter($classes);

$loader = new ClassLoader();
$loader
->addClassMapProvider($provider)
->setBasePath(__DIR__)
->addPath('src')
->addPath('tests');
```