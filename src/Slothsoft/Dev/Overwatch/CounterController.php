<?php
namespace Slothsoft\Dev\Overwatch;

use Slothsoft\Farah\Module\Controllers\ControllerImplementation;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlArguments;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlPath;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlResolver;
use Slothsoft\Farah\Module\PathResolvers\PathResolverCatalog;
use Slothsoft\Farah\Module\PathResolvers\PathResolverInterface;

/**
 *
 * @author Daniel Schulz
 *        
 */
class CounterController extends ControllerImplementation
{
    public function createPathResolver() : PathResolverInterface
    {
        $ret = [];
        
        $asset = $this->getAsset();
        
        $module = $asset->getOwnerModule();
        
        $configUrl = $module->createUrl(
            FarahUrlPath::createFromString('/overwatch/config'),
            FarahUrlArguments::createEmpty()
        );
        $configResult = FarahUrlResolver::resolveToResult($configUrl);
        $configDocument = $configResult->toDocument();
        
        foreach ($configDocument->getElementsByTagName('source') as $sourceNode) {
            $source = [];
            foreach ($sourceNode->attributes as $attr) {
                $source[$attr->name] = $attr->value;
            }
            $source['dataUrl'] = "farah://slothsoft@dev/overwatch/source/$source[name]";
            $source['templateUrl'] = "farah://slothsoft@dev/overwatch/$source[type]-adapter/$source[adapter]";
            
            if ($source['type'] === 'counter') {
                /*
                $ret["/$source[name]"] = $asset->addChildElement($element)
                
                $definition->createClosure([
                    'path' => 
                ], function (FarahUrl $url) use ($dataDoc, $source) {
                    $dom = new DOMHelper();
                    return $dom->transformToDocument($dataDoc, $source['templateUrl'], $source);
                });
                //*/
            }
        }
        
        return PathResolverCatalog::createAssetChildrenResolver($asset);
    }
}

