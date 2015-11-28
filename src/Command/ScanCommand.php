<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScanCommand extends Command
{
    /** @var ISoftwarePackage[] */
    private $softwarePackages;

    /**
     * ScanCommand constructor.
     * @param \Ontic\Yaes\SoftwarePackages\ISoftwarePackage[] $softwarePackages
     */
    public function __construct(array $softwarePackages)
    {
        parent::__construct();
        $this->softwarePackages = $softwarePackages;
    }

    protected function configure()
    {
        $this
            ->setName('scan')
            ->setDescription('Identify and scan a site for known vulnerabilities')
            ->addArgument('url', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $target = Target::createFromString($input->getArgument('url'));

        // Detect the software package
        $softwarePackage = null;
        foreach($this->softwarePackages as $package)
        {
            $package = $package->getIdentifier()->identify($target);
            if($package !== null)
            {
                $softwarePackage = $package;
                break;
            }
        }

        if($softwarePackage === null)
        {
            echo 'Unable to detect the software package' . PHP_EOL;
            return;
        }

        echo 'Detected software: ' . $softwarePackage->getName() . PHP_EOL;

        // Scan for vulnerabilities
        $scanners = $softwarePackage->getScanners();

        foreach($scanners as $scanner)
        {
            switch($scanner->getTargetStatus($target))
            {
                case IScanner::STATUS_SAFE:
                    $status = 'Safe';
                    break;

                case IScanner::STATUS_VULNERABLE:
                    $status = 'Vulnerable';
                    break;

                case IScanner::STATUS_UNKNOWN:
                default:
                    $status = 'Unknown';
            }

            echo $scanner->getName() . ' => ' . $status . PHP_EOL;
        }
    }
}