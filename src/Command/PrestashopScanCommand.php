<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Scanners\ScannerLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrestashopScanCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('prestashop:scan')
            ->setDescription('Scan a Prestashop site for known vulnerabilities')
            ->addArgument('host', InputArgument::REQUIRED)
            ->addOption('base-path', null, InputOption::VALUE_OPTIONAL, '', '')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, '', 80);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $target = new Target(
            $input->getArgument('host'),
            $input->getOption('port'),
            $input->getOption('base-path')
        );

        $scanners = (new ScannerLoader())->getScanners('Prestashop');

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