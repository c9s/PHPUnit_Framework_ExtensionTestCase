PHPUnit Framework ExtensionTestCase
===================================

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

      <!-- test with extension -->
      <testsuite name="FileUtilTest" extension="fileutil">
        <!-- TODO -->
      </testsuite>

      <testsuite name="ArrayTest">
        <directory>tests</directory>
      </testsuite>
  </testsuites>
</phpunit>
```


```php
require "src/PHPUnit/Framework/ExtensionTestCase.php";

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



