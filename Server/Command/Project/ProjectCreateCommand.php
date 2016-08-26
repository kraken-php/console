<?php

namespace Kraken\Console\Server\Command\Project;

use Kraken\Runtime\Command\Command;
use Kraken\Command\CommandInterface;
use Kraken\Config\Config;
use Kraken\Config\ConfigInterface;
use Kraken\Throwable\Exception\Runtime\Execution\RejectionException;

class ProjectCreateCommand extends Command implements CommandInterface
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     *
     */
    protected function construct()
    {
        $config = $this->runtime->getCore()->make('Kraken\Config\ConfigInterface');

        $this->config = $this->createConfig($config);
    }

    /**
     *
     */
    protected function destruct()
    {
        unset($this->config);
    }

    /**
     * @param mixed[] $params
     * @return mixed
     * @throws RejectionException
     */
    protected function command($params = [])
    {
        if (!isset($params['flags']))
        {
            throw new RejectionException('Invalid params.');
        }

        return $this->runtime
            ->manager()
            ->createProcess(
                $this->config->get('main.alias'),
                $this->config->get('main.name'),
                $params['flags']
            )
            ->then(
                function() {
                    return 'Project has been created.';
                }
            )
        ;
    }

    /**
     * Create Config.
     *
     * @param ConfigInterface|null $config
     * @return Config
     */
    protected function createConfig(ConfigInterface $config = null)
    {
        return new Config($config === null ? [] : $config->get('core.project'));
    }
}
