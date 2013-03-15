PHPUnit Framework ExtensionTestCase
===================================

By using this extension testcase and a phpunit-exttest runner, you 
can easily integrate phpunit tests with your extension.



Installation
------------

    $ git clone https://github.com/c9s/PHPUnit_Framework_ExtensionTestCase.git
    $ cd PHPUnit_Framework_ExtensionTestCase
    $ pear channel-discover pear.corneltek.com
    $ pear install -f -a package.xml

Usage
------


The phpunit-exttest runner which runs phpunit with the extension .so file that you've 
compiled in the `modules` directory, e.g.,

```sh
$ phpunit-exttest tests/APCExtensionTest.php
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    bootstrap="tests/bootstrap.php"
    backupGlobals="false"
    verbose="true"
    syntaxCheck="true"
    colors="true"
    convertErrorsToExceptions="true"
    convertNoticesToExceptions="true"
    convertWarningsToExceptions="true">

  <testsuites>

      <!-- test with extension, this launches a new php process with
           modules/fileutil.so extension -->
      <testsuite name="FileUtilTest" extension="fileutil">
        <!-- TODO -->
      </testsuite>

      <testsuite name="ArrayTest">
        <directory>tests</directory>
      </testsuite>
  </testsuites>
</phpunit>
```

And the sample extension testcase class:

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



