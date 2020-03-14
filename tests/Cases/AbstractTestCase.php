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
use GuzzleHttp\Client;
use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Container;
use Hyperf\Guzzle\HandlerStackFactory as HyperfHandlerStackFactory;
use Hyperf\Utils\ApplicationContext;
use HyperfX\Feishu\HandlerStackFactory;
use HyperfX\Feishu\Provider\Message;
use HyperfX\Feishu\Provider\Robot;
use HyperfX\Feishu\Provider\Robots;
use HyperfX\Feishu\Provider\TenantAccessToken;
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
        $container = Mockery::mock(Container::class);
        ApplicationContext::setContainer($container);

        $container->shouldReceive('get')->with(ConfigInterface::class)->andReturn($this->getConfig());
        $container->shouldReceive('get')->with(HandlerStackFactory::class)->andReturn(new HandlerStackFactory($container));
        $container->shouldReceive('get')->with(HyperfHandlerStackFactory::class)->andReturn(new HyperfHandlerStackFactory());
        $container->shouldReceive('get')->with(Robots::class)->andReturnUsing(function ($_) use ($container) {
            return new Robots($container);
        });
        $container->shouldReceive('make')->with(Robot::class, Mockery::any())->andReturnUsing(function ($_, $args) use ($container) {
            return new Robot($container, $args['conf']);
        });
        $container->shouldReceive('make')->with(TenantAccessToken::class, Mockery::any())->andReturnUsing(function ($_, $args) use ($container) {
            return new TenantAccessToken(...$args);
        });
        $container->shouldReceive('make')->with(Client::class, Mockery::any())->andReturnUsing(function ($_, $args) {
            return new Client(...$args);
        });
        $container->shouldReceive('get')->with(Message::class)->andReturn(new Message($container));
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
                        'app_secret' => env('FEISHU_BOT_SECRET', ''),
                    ],
                ],
            ],
        ]);
    }
}
