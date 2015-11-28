<?php

namespace Ontic\Yaes\Scanners\Magento;

use DOMDocument;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Model\Target;

class Supee6788Scanner implements IScanner
{
    /**
     * @param Target $target
     * @return int
     */
    function getTargetStatus(Target $target)
    {
        $url = sprintf('http://%s:%d/%scustomer/account/create/',
            $target->getHost(),
            $target->getPort(),
            $target->getBasePath());

        if(($responseBody = file_get_contents($url)) === false)
        {
            return IScanner::STATUS_UNKNOWN;
        }

        $htmlDocument = new DOMDocument();
        @$htmlDocument->loadHTML($responseBody);

        foreach($htmlDocument->getElementsByTagName('input') as $input)
        {
            if($input->getAttribute('name') === 'form_key')
            {
                return IScanner::STATUS_SAFE;
            }
        }

        return IScanner::STATUS_VULNERABLE;
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'SUPEE-6788';
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return null;
    }
}