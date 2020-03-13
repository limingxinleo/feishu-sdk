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

namespace HyperfTest\Cases;

use Dotenv\Dotenv;
use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var bool
     */
    protected $isMock = true;

    protected function tearDown()
    {
        Mockery::close();
    }

    protected function getContainer(): ContainerInterface
    {
        $container = Mockery::mock(ContainerInterface::class);
        ApplicationContext::setContainer($container);

        $container->shouldReceive('get')->with(ConfigInterface::class)->andReturn($this->getConfig());

        return $container;
    }

    protected function getConfig(): ConfigInterface
    {
        if (file_exists(BASE_PATH . '/.env')) {
            $this->isMock = false;
            Dotenv::create(BASE_PATH)->load();
        }

        return new Config([
            'feishu' => [
                'guzzle' => [
                    'config' => [
                        'base_uri' => 'https://open.feishu.cn',
                        'timeout' => 2,
                    ],
                ],
                'robots' => [
                    'default' => [
                        'app_id' => env('FEISHU_BOT_APPID', ''),
                        'secret' => env('FEISHU_BOT_SECRET', ''),
                    ],
                ],
            ],
        ]);
    }
}
