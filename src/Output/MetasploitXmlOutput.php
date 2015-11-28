<?php

namespace Ontic\Yaes\Output;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class MetasploitXmlOutput implements IOutput
{
    private $softwarePackage;
    private $currentDateTime;
    private $output;
    /** @var string[] */
    private $vulns;

    function writeSoftwareDetecionResult(Target $target, ISoftwarePackage $softwarePackage = null)
    {
        $this->currentDateTime = (new \DateTime())->format('Y-m-d H:i:s');
        $this->softwarePackage = $softwarePackage;

        $this->output = $this->loadTemplate('metasploit_template.xml');
    }

    function writeScanResult(Target $target, IScanner $scanner, $result)
    {
        if($result !== IScanner::STATUS_VULNERABLE)
        {
            return;
        }

        $vulnOutput = $this->loadTemplate('metasploit_vuln_template.xml');
        $vulnOutput = $this->setValue($vulnOutput, 'vuln_counter', count($this->vulns) + 1);
        $vulnOutput = $this->setValue($vulnOutput, 'current_date', $this->currentDateTime);

        $this->vulns[] = $vulnOutput;
    }

    function finish()
    {
        $vulnsString = implode('', $this->vulns);
        echo $this->setValue($this->output, 'vulns', $vulnsString);
    }

    private function setValue($string, $name, $value)
    {
        return str_replace('{{ ' . $name. ' }}', $value, $string);
    }

    private function loadTemplate($name)
    {
        return file_get_contents(__DIR__ . '/' . $name);
    }

}