PHPUnit Framework ExtensionTestCase
===================================

Installation
------------

composer:

```json
{
    require: {
        "corneltek/phpunit-framework-extensiontestcase": "dev-master"
    }
}
```

```sh
$ pear channel-discover pear.corneltek.com
$ pear install corneltek/PHPUnit_Framework_ExtensionTestCase
```

Usage
------

The Sample ExtensionTestcase class:

```php

class FileUtilTest extends PHPUnit_Framework_ExtensionTestCase
{

    public function getExtensionName()
    {
        return 'fileutil';
    }


    public function getFunctions()
    {
        return array(
            'futil_scandir',
            'futil_scandir_dir',
        );
    }

    public function testScanDirOnExistingDir()
    {
        $files = futil_scandir("/etc");
        ok($files,"Should return a file list");
        ok(is_array($files));
        foreach($files as $file) {
            path_ok($file);
        }
    }

    public function testScanDirOnExistingFile()
    {
        $files = futil_scandir("tests/FileUtilTest.php");
        is(false,$files,"Should return false on file path");
    }

    public function testScanDirOnNonExistingPath()
    {
        $files = futil_scandir("blah/blah");
        is(false,$files,"Should return false on file path");
    }


    public function testScanDirDir()
    {
        $paths = futil_scandir_dir("/");
        foreach($paths as $path) {
            ok(is_dir($path),'is_dir ok');
        }
    }
}
```



