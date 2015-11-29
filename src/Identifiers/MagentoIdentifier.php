<?php

namespace Ontic\Yaes\Identifiers;

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
        $request = curl_init($target->getUrl('customer/account/login/'));
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $responseBody = curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        if($responseCode !== 200 || strpos($responseBody, 'customer/account/loginPost') === false)
        {
            return null;
        }

        return $this->package;
    }
}