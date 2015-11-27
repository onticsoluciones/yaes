<?php

namespace Ontic\Yaes\Command;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Model\Target;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IdentifyCommand extends Command
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

        foreach($this->getIdentifiers() as $identifier)
        {
            $software = $identifier->identify($target);
            if($software !== IIdentifier::UNKNOWN)
            {
                switch($software)
                {
                    case IIdentifier::MAGENTO:
                        echo 'Magento' . PHP_EOL;
                        return;

                    case IIdentifier::PRESTASHOP:
                        echo 'Prestashop' . PHP_EOL;
                        return;

                    case IIdentifier::VIRTUEMART:
                        echo 'VirtueMart' . PHP_EOL;
                        return;

                    case IIdentifier::OSCOMMERCE:
                        echo 'osCommerce' . PHP_EOL;
                        return;

                    case IIdentifier::WOOCOMMERCE:
                        echo 'WooCommerce' . PHP_EOL;
                        return;
                }
            }
        }

        echo 'Unknown' . PHP_EOL;
    }

    /**
     * @return IIdentifier[]
     */
    private function getIdentifiers()
    {
        $identifiers = [];

        $identifiersDir = $this->basePath . '/src/Identifiers';

        foreach(scandir($identifiersDir) as $entry)
        {
            $fullPath = $identifiersDir . '/' . $entry;

            if(!is_file($fullPath))
            {
                continue;
            }

            $className = pathinfo($entry)['filename'];
            $fullyQualifiedClassName = 'Ontic\Yaes\Identifiers\\' . $className;
            if(class_exists($fullyQualifiedClassName))
            {
                $identifiers[] = new $fullyQualifiedClassName();
            }
        }

        return $identifiers;
    }
}