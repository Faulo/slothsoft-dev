<?php
declare(strict_types = 1);
namespace Slothsoft\Dev\Overwatch;

use Slothsoft\Farah\Module\FarahUrl\FarahUrlArguments;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlPath;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlResolver;
use Slothsoft\Farah\Module\Node\Asset\ContainerAsset;
use Slothsoft\Farah\Module\Node\Enhancements\AssetBuilderTrait;

/**
 *
 * @author Daniel Schulz
 *        
 */
class SourceAsset extends ContainerAsset
{
    use AssetBuilderTrait;

    protected function loadChildren(): array
    {
        $ret = [];
        $module = $this->getOwnerModule();
        
        $configUrl = $module->createUrl(FarahUrlPath::createFromString('/overwatch/config'), FarahUrlArguments::createEmpty());
        $configResult = FarahUrlResolver::resolveToResult($configUrl);
        $configDocument = $configResult->toDocument();
        
        foreach ($configDocument->getElementsByTagName('source') as $sourceNode) {
            $source = [];
            foreach ($sourceNode->attributes as $attr) {
                $source[$attr->name] = $attr->value;
            }
            
            $ret[] = $this->buildExternalResource($source['name'], $source['href']);
        }
        return $ret;
    }
}

