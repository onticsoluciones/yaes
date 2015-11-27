<?php

namespace Ontic\Yaes\Loaders;

use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class SoftwarePackagesLoader
{
    /**
     * @param string $directory
     * @param string $namespace
     * @return ISoftwarePackage[]
     */
    public function findSoftwarePackages($directory, $namespace)
    {
        $softwarePackages = [];

        foreach(scandir($directory) as $entry)
        {
            $fullPath = $directory . '/' . $entry;
            if(!file_exists($fullPath))
            {
                continue;
            }

            $className = pathinfo($entry)['filename'];
            $fullClassName = $namespace . '\\' . $className;
            if(class_exists($fullClassName))
            {
                $softwarePackages[] = new $fullClassName();
            }
        }

        return $softwarePackages;
    }

}