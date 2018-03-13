<?php
declare(strict_types = 1);
namespace Slothsoft\Dev\Overwatch;

use Slothsoft\Core\XML\LeanElement;
use Slothsoft\Farah\Module\Module;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlArguments;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlPath;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlResolver;
use Slothsoft\Farah\Module\Node\Asset\ContainerAsset;

/**
 *
 * @author Daniel Schulz
 *        
 */
class SourceAsset extends ContainerAsset
{

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
            $element = LeanElement::createOneFromArray(Module::TAG_EXTERNAL_DOCUMENT, [
                'name' => $source['name'],
                'href' => $source['href']
            ]);
            
            $ret[] = $this->createChildNode($element);
        }
        return $ret;
    }
}

