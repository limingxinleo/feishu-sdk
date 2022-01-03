<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Fan\Feishu;

use Fan\Feishu\Exception\InvalidArgumentException;
use Hyperf\Contract\ConfigInterface;

class Factory
{
    /**
     * @var Application[]
     */
    protected array $applications = [];

    protected array $config = [];

    public function __construct(ConfigInterface $config)
    {
        $this->config = $this->formatConfig($config);
    }

    public function get(string $name): Application
    {
        $app = $this->applications[$name] ?? null;
        if ($app instanceof Application) {
            return $app;
        }

        return $this->applications[$name] = $this->make($name);
    }

    public function make(string $name): Application
    {
        $config = $this->config['applications'][$name] ?? null;
        if (empty($config)) {
            throw new InvalidArgumentException(sprintf('Config %s is invalid.', $name));
        }

        $http = $this->config['http'] ?? [];

        return new Application(array_replace_recursive(['http' => $http], $config));
    }

    /**
     * @return [
     *     'http' => [
     *         'base_uri' => '',
     *         'timeout' => 2,
     *     ],
     *     'applications' => [
     *         'default' => [
     *             'app_id' => '',
     *             'app_secret' => '',
     *             'http' => [
     *                  'base_uri' => '',
     *                  'timeout' => 2,
     *             ],
     *         ],
     *     ],
     * ]
     */
    private function formatConfig(ConfigInterface $config): array
    {
        return $config->get('feishu', [
            'http' => [
                'base_uri' => 'https://open.feishu.cn',
                'timeout' => 2,
            ],
            'applications' => [],
        ]);
    }
}
