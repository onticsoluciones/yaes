<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IdentifyCommand extends Command
{
    /** @var ISoftwarePackage[] */
    private $softwarePackages;

    /**
     * @param ISoftwarePackage[] $packages
     */
    public function __construct($packages)
    {
        parent::__construct();
        $this->softwarePackages = $packages;
    }

    protected function configure()
    {
        $this
            ->setName('identify')
            ->setDescription('Try to guess the software running behind a site')
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

        foreach($this->softwarePackages as $package)
        {
            $software = $package->getIdentifier()->identify($target);
            if($software !== IIdentifier::UNKNOWN)
            {
                echo $package->getName() . PHP_EOL;
                return;
            }
        }

        echo 'Unknown' . PHP_EOL;
    }
}