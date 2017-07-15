<?php

namespace Akuma\Tool\DbManager\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DatabaseRestoreCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('snapshot:restore')
            ->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, '', null)
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, '', (new \DateTime())->format('Ymdhis'));
    }
}
