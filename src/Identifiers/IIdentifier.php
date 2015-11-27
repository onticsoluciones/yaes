<?php

namespace Ontic\Yaes\Identifiers;

use Ontic\Yaes\Model\Target;

interface IIdentifier
{
    const UNKNOWN = -1;
    const MAGENTO = 1;
    const PRESTASHOP = 2;
    const VIRTUEMART = 3;
    const WOOCOMMERCE = 4;
    const OSCOMMERCE = 5;

    /**
     * @param Target $target
     * @return int|null
     */
    function identify(Target $target);
}