<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrestashopScanCommand extends Command
{
    /** @var ISoftwarePackage */
    private $package;

    /**
     * @param ISoftwarePackage $package
     */
    public function __construct(ISoftwarePackage $package)
    {
        parent::__construct();
        $this->package = $package;
    }

    protected function configure()
    {
        $this
            ->setName('prestashop:scan')
            ->setDescription('Scan a Prestashop site for known vulnerabilities')
            ->addArgument('url', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $target = Target::createFromString($input->getArgument('url'));

        $scanners = $this->package->getScanners();

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