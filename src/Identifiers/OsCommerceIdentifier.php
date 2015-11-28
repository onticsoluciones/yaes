<?php

namespace Ontic\Yaes\Identifiers;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class OsCommerceIdentifier implements IIdentifier
{
    /** @var ISoftwarePackage */
    private $softwarePackage;

    /**
     * OsCommerceIdentifier constructor.
     * @param ISoftwarePackage $softwarePackage
     */
    public function __construct(ISoftwarePackage $softwarePackage)
    {
        $this->softwarePackage = $softwarePackage;
    }

    /**
     * @param Target $target
     * @return ISoftwarePackage|null
     */
    function identify(Target $target)
    {
        $request = curl_init($target->getUrl('conditions.php'));
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        if($responseCode !== 200)
        {
            return null;
        }

        // osCommerce with respond with a 403 to a request for
        // includes/classes/osc_template.php
        $request = curl_init($target->getUrl('includes/classes/osc_template.php'));
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        if($responseCode === 403)
        {
            return $this->softwarePackage;
        }

        return null;
    }
}