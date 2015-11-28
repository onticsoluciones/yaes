<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            ->addArgument('url', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $target = Target::createFromString($input->getArgument('url'));

        foreach($this->softwarePackages as $package)
        {
            $package = $package->getIdentifier()->identify($target);
            if($package !== null)
            {
                echo $package->getName() . PHP_EOL;
                return;
            }
        }

        echo 'Unknown' . PHP_EOL;
    }
}