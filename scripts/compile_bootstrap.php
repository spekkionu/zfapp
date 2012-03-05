<?php
require(__DIR__.'/../src/system/application.php');
// Combine and compress bootstrap files.

$input = array( new Assetic\Asset\FileAsset(WEBROOT.DIRECTORY_SEPARATOR.'assets/scripts/bootstrap/less/bootstrap.less') );

require(SYSTEM.'/library/vendor/lessphp/lessc.inc.php');
$css = new Assetic\Asset\AssetCollection($input, array(
  new Assetic\Filter\LessphpFilter(),
  new Assetic\Filter\CssImportFilter(),
  new Assetic\Filter\Yui\CssCompressorFilter(__DIR__.'/tasks/compressor/yuicompressor.jar', 'java'),
));

try{
  file_put_contents(WEBROOT."/assets/styles/bootstrap/css/bootstrap.min.css", $css->dump());
}catch(Exception $e){
  exit($e->getMessage());
}
