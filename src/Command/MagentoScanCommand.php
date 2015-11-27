<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MagentoScanCommand extends Command
{
    /** @var string */
    private $basePath;

    /**
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        parent::__construct();
        $this->basePath = $basePath;
    }

    protected function configure()
    {
        $this
            ->setName('magento:scan')
            ->setDescription('Scan a Magento site for known vulnerabilities')
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

        foreach($this->getMagentoScanners() as $scanner)
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

    /**
     * @return IScanner[]
     */
    private function getMagentoScanners()
    {
        $scanners = [];

        $magentoScannersDir = $this->basePath . '/src/Scanners/Magento';

        foreach(scandir($magentoScannersDir) as $entry)
        {
            $fullPath = $magentoScannersDir . '/' . $entry;

            if(!is_file($fullPath))
            {
                continue;
            }

            $className = pathinfo($entry)['filename'];
            $fullyQualifiedClassName = 'Ontic\Yaes\Scanners\Magento\\' . $className;
            $scanners[] = new $fullyQualifiedClassName();
        }

        return $scanners;
    }
}