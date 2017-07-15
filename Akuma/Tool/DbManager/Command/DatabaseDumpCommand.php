<?php

namespace Akuma\Tool\DbManager\Command;

use Oro\Component\Database\Engine\DatabaseEngineInterface;
use Oro\Component\Database\Model\DatabaseConfigurationModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseDumpCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('snapshot:dump')
            ->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, '', null)
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, '', (new \DateTime())->format('Ymdhis'));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection($input->getOption('connection'));

        $configuration = new DatabaseConfigurationModel();
        $configuration->setDbName($connection->getDatabase())
            ->setDriver($connection->getDriver()->getName())
            ->setHost($connection->getHost())
            ->setPort($connection->getPort())
            ->setUser($connection->getUsername())
            ->setPassword($connection->getPassword());

        /** @var DatabaseEngineInterface $isolator */
        $isolator = $this->getContainer()->get('oro_datasnap.engine.registry')
            ->findEngine($configuration);
        $sid = $input->getOption('id');
        $isolator->dump($sid, $configuration);
        $output->writeln(sprintf('Generated dump with sid <info>%s</info>', $sid));
    }

}
