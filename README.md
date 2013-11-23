A psr4 compatible class loader for php5.3+
==========================================

set_include_path="on" required!

```php
ClassLoader::getInstance()
->includeNamespace('Kir\\*')
->setBasePath(__DIR__)
->addPath('src')
->addPath('tests');
```