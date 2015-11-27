<?php

namespace Ontic\Yaes\Identifiers;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class VirtueMartIdentifier implements IIdentifier
{
    /** @var  */
    private $package;

    /**
     * VirtueMartIdentifier constructor.
     * @param $package
     */
    public function __construct($package)
    {
        $this->package = $package;
    }

    /**
     * @param Target $target
     * @return ISoftwarePackage|null
     */
    function identify(Target $target)
    {
        // components/com_virtuemart/router.php should return status 200
        $request = curl_init($target->getUrl('components/com_virtuemart/router.php'));
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        if($responseCode === 200)
        {
            return $this->package;
        }

        return null;
    }
}