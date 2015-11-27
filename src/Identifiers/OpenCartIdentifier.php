<?php

namespace Ontic\Yaes\Identifiers;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class OpenCartIdentifier implements IIdentifier
{
    /** @var ISoftwarePackage */
    private $software;

    /**
     * @param ISoftwarePackage $software
     */
    public function __construct(ISoftwarePackage $software)
    {
        $this->software = $software;
    }

    /**
     * @param Target $target
     * @return ISoftwarePackage|null
     */
    function identify(Target $target)
    {
        // This file should always exist on an OpenCart installation
        // /admin/language/english/localisation/weight_class.php
        $request = curl_init($target->getUrl('admin/language/english/localisation/weight_class.php'));
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        if($responseCode === 200)
        {
            return $this->software;
        }

        return null;
    }
}