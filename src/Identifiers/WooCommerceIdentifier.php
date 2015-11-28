<?php

namespace Ontic\Yaes\Identifiers;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class WooCommerceIdentifier implements IIdentifier
{
    /** @var ISoftwarePackage */
    private $softwarePackage;

    /**
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
        $request = curl_init($target->getUrl('wp-login.php'));
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        if($responseCode !== 200)
        {
            return null;
        }

        // WooCommerce sites should response with a status 403 to
        // /wp-content/plugins/woocommerces
        $request = curl_init($target->getUrl('wp-content/plugins/woocommerce/'));
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