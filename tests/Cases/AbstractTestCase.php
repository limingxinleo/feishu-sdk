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
use GuzzleHttp\Psr7\Response;
use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Container;
use Hyperf\Guzzle\CoroutineHandler;
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
            if ($this->isMock) {
                $client = Mockery::mock(Client::class);
                $client->shouldReceive('post')->andReturnUsing(function ($uri, $args) {
                    return new Response(200, [], $this->getContent($uri));
                });
                $client->shouldReceive('get')->andReturnUsing(function ($uri, $args) {
                    return new Response(200, [], $this->getContent($uri));
                });
                return $client;
            }
            return new Client(...$args);
        });
        $container->shouldReceive('make')->with(CoroutineHandler::class)->withAnyArgs()->andReturn(new CoroutineHandler());
        $container->shouldReceive('get')->with(Message::class)->andReturn(new Message($container));
        return $container;
    }

    protected function getContent(string $uri): string
    {
        $path = BASE_PATH . '/tests/json/';
        $maps = [
            '/open-apis/auth/v3/tenant_access_token/internal/' => file_get_contents($path . 'access_token.json'),
            '/open-apis/chat/v4/list' => file_get_contents($path . 'chat_list.json'),
            '/open-apis/bot/v3/info/' => file_get_contents($path . 'info.json'),
            '/open-apis/message/v4/send/' => file_get_contents($path . 'send.json'),
        ];

        return $maps[$uri];
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
