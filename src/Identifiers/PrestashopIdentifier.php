<?php

namespace Ontic\Yaes\Identifiers;

use DOMDocument;
use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class PrestashopIdentifier implements IIdentifier
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

        foreach($htmlDocument->getElementsByTagName('link') as $meta)
        {
            if(strstr($meta->getAttribute('href'), 'blockcart.css') !== false)
            {
                return $this->package;
            }
        }

        foreach($htmlDocument->getElementsByTagName('meta') as $meta)
        {
            if($meta->getAttribute('name') === 'generator' && strstr($meta->getAttribute('content'), 'PrestaShop') !== false)
            {
                return $this->package;
            }
        }

        return null;
    }
}