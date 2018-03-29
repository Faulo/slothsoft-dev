<?php
namespace Slothsoft\Farah;

$resDoc = $this->getResourceDoc('dev/unicode', 'xml');

$nodeList = $resDoc->getElementsByTagName('letter');

$unicodeMap = [];

foreach ($nodeList as $node) {
    foreach ($node->attributes as $attrNode) {
        $type = $attrNode->name;
        $letter = $attrNode->value;
        
        if (! isset($unicodeMap[$type])) {
            $unicodeMap[$type] = [];
        }
        $unicodeMap[$type][] = $letter;
    }
}

unset($unicodeMap['name']);

$ret = json_encode($unicodeMap);

$this->progressStatus |= self::STATUS_RESPONSE_SET;
$this->httpResponse->setStatus(HTTPResponse::STATUS_OK);
$this->httpResponse->setBody($ret);
$this->httpResponse->setEtag(HTTPResponse::calcEtag($ret));