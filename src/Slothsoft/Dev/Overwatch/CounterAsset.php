<?php
declare(strict_types = 1);
namespace Slothsoft\Dev\Overwatch;

use Slothsoft\Core\XML\LeanElement;
use Slothsoft\Farah\Module\Module;
use Slothsoft\Farah\Module\Node\Asset\ContainerAsset;
use Slothsoft\Farah\Module\FarahUrl\FarahUrl;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlArguments;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlPath;
use Slothsoft\Farah\Module\FarahUrl\FarahUrlResolver;
use Slothsoft\Farah\Module\Results\DOMDocumentResult;
use Slothsoft\Farah\Module\Results\ResultInterface;

/**
 *
 * @author Daniel Schulz
 *        
 */
class CounterAsset extends ContainerAsset
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
            
            $useDocument1 = LeanElement::createOneFromArray(Module::TAG_USE_DOCUMENT, [
                'ref' => '/overwatch/config'
            ]);
            $useDocument2 = LeanElement::createOneFromArray(Module::TAG_USE_DOCUMENT, [
                'ref' => "/overwatch/source/$source[name]",
                'as' => 'source'
            ]);
            $useTemplate = LeanElement::createOneFromArray(Module::TAG_USE_TEMPLATE, [
                'ref' => "/overwatch/$source[type]-adapter/$source[adapter]"
            ]);
            $fragment = LeanElement::createOneFromArray(Module::TAG_FRAGMENT, [
                'name' => $source['name']
            ], [
                $useDocument1,
                $useDocument2,
                $useTemplate
            ]);
            
            $ret[] = $this->createChildNode($fragment);
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

