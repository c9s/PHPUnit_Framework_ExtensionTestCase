PHPUnit Framework ExtensionTestCase
===================================

By using this extension testcase and a phpunit-exttest runner, you 
can easily integrate phpunit tests with your extension.

The phpunit-exttest runner takes the parameters from `run-tests.sh`

```sh
$ phpunit-exttest

/Users/c9s/.phpbrew/php/php-5.4.10/bin/php -d 'output_handler=' -d
'open_basedir=' -d 'safe_mode=0' -d 'disable_functions=' -d
'output_buffering=Off' -d 'error_reporting=32767' -d 'display_errors=1' -d
'display_startup_errors=1' -d 'log_errors=0' -d 'html_errors=0' -d
'track_errors=1' -d 'report_memleaks=1' -d 'report_zend_debug=0' -d
'docref_root=' -d 'docref_ext=.html' -d 'error_prepend_string=' -d
'error_append_string=' -d 'auto_prepend_file=' -d 'auto_append_file=' -d
'magic_quotes_runtime=0' -d 'ignore_repeated_errors=0' -d 'precision=14' -d
'memory_limit=128M' -d
'extension_dir=/Users/c9s/git/Work/php-fileutil-ext/modules/' -d
'session.auto_start=0' -d 'zlib.output_compression=Off' -d
'mbstring.func_overload=0' -n -d extension=fileutil.so
/Users/c9s/.phpbrew/php/php-5.4.10/bin/phpunit tests
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



