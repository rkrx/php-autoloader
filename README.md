A class loader for php5.3+
==========================================

 * psr4 compatible
 * set_include_path="on" is required!

```php
ClassLoader::getInstance()
->excludeNamespace('*')
->includeNamespace('Kir\\*')
->setBasePath(__DIR__)
->addPath('src')
->addPath('tests');
```