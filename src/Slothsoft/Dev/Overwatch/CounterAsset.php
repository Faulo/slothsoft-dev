<?php
declare(strict_types = 1);
namespace Slothsoft\Dev\Overwatch;

use Slothsoft\Farah\Module\FarahUrl\FarahUrl;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlArguments;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlPath;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlResolver;
use Slothsoft\Farah\Module\Node\Asset\ContainerAsset;
use Slothsoft\Farah\Module\Results\DOMDocumentResult;
use Slothsoft\Farah\Module\Results\ResultInterface;
use Slothsoft\Farah\Module\Node\Enhancements\AssetBuilderTrait;

/**
 *
 * @author Daniel Schulz
 *        
 */
class CounterAsset extends ContainerAsset
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
            
            $ret[] = $this->buildFragment(
                $source['name'],
                [
                    'config' => '/overwatch/config',
                    'source' => "/overwatch/source/$source[name]",
                ],
                "/overwatch/$source[type]-adapter/$source[adapter]"
            );
        }
        return $ret;
    }

    protected function loadResult(FarahUrl $url): ResultInterface
    {
        $document = $this->getElement()->toDocument();
        $parentNode = $document->documentElement;
        foreach ($this->getAssetChildren() as $child) {
            $parentNode->appendChild($child->lookupResultByArguments($url->getArguments())
                ->toElement($document));
        }
        return new DOMDocumentResult($url, $document);
    }
}

