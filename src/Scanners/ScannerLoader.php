<?php

namespace Ontic\Yaes\Scanners;

class ScannerLoader
{
    /**
     * @param $namespace
     * @return IScanner[]
     */
    public function getScanners($namespace)
    {
        $scanners = [];

        $scannersDir = __DIR__ . '/' . $namespace;

        foreach(scandir($scannersDir) as $entry)
        {
            $fullPath = $scannersDir . '/' . $entry;

            if(!is_file($fullPath))
            {
                continue;
            }

            $className = pathinfo($entry)['filename'];
            $fullyQualifiedClassName = 'Ontic\Yaes\Scanners\\' . $namespace . '\\' . $className;
            $scanners[] = new $fullyQualifiedClassName();
        }

        return $scanners;
    }
}