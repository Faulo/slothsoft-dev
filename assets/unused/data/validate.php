<?php

use Slothsoft\Core\FileSystem;
use Slothsoft\Core\ServerEnvironment;

$ret = [];

$moduleList = FileSystem::scanDir(ServerEnvironment::getRootDirectory() . 'vendor/slothsoft', FileSystem::SCANDIR_REALPATH);

foreach ($moduleList as $modulePath) {
    $moduleName = basename($modulePath);
    $ret["/$moduleName"] = $this->createClosure(
        [],
        function() use ($modulePath, $moduleName) {
            $ret = new DOMDocument();
            
            $ret->appendChild($ret->createElement('result'));
            $ret->documentElement->textContent = "validating module $moduleName...";
            
            return $ret;
        }
    );
}

return $ret;