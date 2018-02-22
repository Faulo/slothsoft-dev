<?php
use Slothsoft\Core\DOMHelper;
use Slothsoft\Core\Storage;
use Slothsoft\Farah\Module\Assets\AssetInterface;

$ret = [];

$module = $this->getOwnerModule();

$asset = $module->getAsset('/overwatch/config');


$dataDoc = $asset->toDocument();

foreach ($dataDoc->getElementsByTagName('source') as $sourceNode) {
	$source = [];
	foreach ($sourceNode->attributes as $attr) {
		$source[$attr->name] = $attr->value;
	}
	$source['dataUrl'] 		= "farah://slothsoft@dev/overwatch/source/$source[name]";
	$source['templateUrl'] 	= "farah://slothsoft@dev/overwatch/$source[type]-adapter/$source[adapter]";
	
	if ($source['type'] === 'counter') {
		$ret[] = $this->createClosure(
			['path' => "/$source[name]"],
			function(AssetInterface $asset) use ($dataDoc, $source) {
				$dom = new DOMHelper();
				return $dom->transformToDocument($dataDoc, $source['templateUrl'], $source);
			}
		);
	}
}

return $ret;