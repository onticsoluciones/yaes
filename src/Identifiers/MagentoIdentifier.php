<?php

namespace Ontic\Yaes\Identifiers;

use DOMDocument;
use Ontic\Yaes\Model\Target;

class MagentoIdentifier implements IIdentifier
{
    /**
     * @param Target $target
     * @return int|null
     */
    function identify(Target $target)
    {
        $url = sprintf('http://%s:%d/%s',
            $target->getHost(),
            $target->getPort(),
            $target->getBasePath());

        if(($responseBody = @file_get_contents($url)) === false)
        {
            return IIdentifier::UNKNOWN;
        }

        $htmlDocument = new DOMDocument();
        @$htmlDocument->loadHTML($responseBody);

        foreach($htmlDocument->getElementsByTagName('script') as $script)
        {
            if(strstr($script->getAttribute('src'), 'mage/cookies.js') !== false)
            {
                return IIdentifier::MAGENTO;
            }
        }

        return IIdentifier::UNKNOWN;
    }
}