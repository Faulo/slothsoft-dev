<?php
use Slothsoft\Core\Storage;
use Slothsoft\Farah\Module\Node\Asset\AssetInterface;

$ret = [];

$module = $this->getOwnerModule();

$asset = $module->getAsset('/overwatch/config');

$dataDoc = $asset->toDocument();

foreach ($dataDoc->getElementsByTagName('source') as $sourceNode) {
    $source = [];
    foreach ($sourceNode->attributes as $attr) {
        $source[$attr->name] = $attr->value;
    }
    
    $ret[] = $this->createClosure([
        'path' => "/$source[name]"
    ], function (AssetInterface $asset) use ($source) {
        return Storage::loadExternalDocument($source['href'], TIME_DAY);
    });
}

return $ret;