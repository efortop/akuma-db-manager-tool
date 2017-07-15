<?php
namespace Akuma\Tool\DbManager\Console;

use Akuma\Tool\DbManager\Command;
use Oro\Component\Database\Engine\MysqlDatabaseEngine;
use Oro\Component\Database\Engine\PgsqlDatabaseEngine;
use Oro\Component\Database\Service\DatabaseEngineRegistry;
use Oro\Component\Database\Service\ProcessExecutor;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Application extends BaseApplication
{
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct('Akuma DB Manager');

        $commands = [
            new Command\DatabaseDumpCommand(),
            new Command\DatabaseRestoreCommand(),
        ];

        $this->injectServices($kernel);

        foreach ($commands as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($kernel->getContainer());
            }
            $this->add($command);
        }
    }

    /**
     * @param KernelInterface $kernel
     */
    private function injectServices(KernelInterface $kernel)
    {
        /** @var Container $container */
        $container = $kernel->getContainer();
        $container->set('oro_datasnap.engine.registry', $this->getRegistry());
    }

    /**
     * @return DatabaseEngineRegistry
     */
    private function getRegistry()
    {
        $registry = new DatabaseEngineRegistry();
        $processExecutor = new ProcessExecutor();
        $registry->addEngine(new MysqlDatabaseEngine($processExecutor), 'mysql');
        $registry->addEngine(new PgsqlDatabaseEngine($processExecutor), 'pgsql');

        return $registry;
    }
}
