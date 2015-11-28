<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Output\ConsoleOutput;
use Ontic\Yaes\Output\IOutput;
use Ontic\Yaes\Output\MetasploitXmlOutput;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addArgument('url', InputArgument::REQUIRED)
            ->addOption('output', '', InputOption::VALUE_OPTIONAL, '', 'stdout');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = $this->getOutputType($input->getOption('output'));
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

        $output->writeSoftwareDetecionResult($target, $softwarePackage);

        // Scan for vulnerabilities
        $scanners = $softwarePackage->getScanners();

        foreach($scanners as $scanner)
        {
            $status = $scanner->getTargetStatus($target);
            $output->writeScanResult($target, $scanner, $status);
        }

        $output->finish();
    }

    /**
     * @param string $name
     * @return IOutput
     */
    private function getOutputType($name)
    {
        switch($name)
        {
            case 'metasploit':
                return new MetasploitXmlOutput();

            case 'console':
            default:
                return new ConsoleOutput();
        }
    }
}