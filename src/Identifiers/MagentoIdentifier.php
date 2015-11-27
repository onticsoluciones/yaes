<?php

namespace Ontic\Yaes\Identifiers;

use DOMDocument;
use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class MagentoIdentifier implements IIdentifier
{
    /** @var ISoftwarePackage */
    private $package;

    /**
     * @param ISoftwarePackage $package
     */
    public function __construct(ISoftwarePackage $package)
    {
        $this->package = $package;
    }

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
            return null;
        }

        $htmlDocument = new DOMDocument();
        @$htmlDocument->loadHTML($responseBody);

        foreach($htmlDocument->getElementsByTagName('script') as $script)
        {
            if(strstr($script->getAttribute('src'), 'mage/cookies.js') !== false)
            {
                return $this->package;
            }
        }

        return false;
    }
}