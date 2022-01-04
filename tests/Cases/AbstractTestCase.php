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
use Dotenv\Repository\Adapter;
use Dotenv\Repository\RepositoryBuilder;
use Fan\Feishu\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Container;
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

    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function getContainer(): ContainerInterface
    {
        $container = Mockery::mock(Container::class);
        ApplicationContext::setContainer($container);

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

        $container->shouldReceive('get')->with(Factory::class)->andReturn(new Factory($this->getConfig()));
        return $container;
    }

    protected function getFactory()
    {
        return new Factory($this->getConfig());
    }

    protected function getContent(string $uri): string
    {
        $path = BASE_PATH . '/tests/json/';
        $maps = [
            'open-apis/auth/v3/tenant_access_token/internal/' => file_get_contents($path . 'access_token.json'),
            'open-apis/chat/v4/list' => file_get_contents($path . 'chat_list.json'),
            'open-apis/bot/v3/info/' => file_get_contents($path . 'info.json'),
            'open-apis/message/v4/send/' => file_get_contents($path . 'send.json'),
            'open-apis/contact/v3/users/batch_get_id' => file_get_contents($path . 'batch_get_id.json'),
            'open-apis/contact/v3/users/ou_983ee36cffcf4417884b0df4f3ff6918' => file_get_contents($path . 'user.json'),
            'open-apis/contact/v3/departments/od-ff11e52d60abebad0ddd06572a6e9468' => file_get_contents($path . 'department.json'),
            'open-apis/contact/v3/departments/0/children' => file_get_contents($path . 'department_children.json'),
            'open-apis/contact/v3/users/find_by_department' => file_get_contents($path . 'users_by_department.json'),
            'open-apis/authen/v1/access_token' => file_get_contents($path . 'oauth_user_info.json'),
        ];

        return $maps[$uri];
    }

    protected function getConfig(): ConfigInterface
    {
        if (file_exists(BASE_PATH . '/.env')) {
            $this->isMock = false;
            $this->loadDotenv();
        }

        return new Config([
            'feishu' => [
                'http' => [
                    'base_uri' => 'https://open.feishu.cn',
                    'timeout' => 2,
                ],
                'applications' => [
                    'default' => [
                        'app_id' => env('FEISHU_APPID', 'cli_a1442e36bcf95013'),
                        'app_secret' => env('FEISHU_SECRET', ''),
                    ],
                    'robot' => [
                        'app_id' => env('FEISHU_BOT_APPID', ''),
                        'app_secret' => env('FEISHU_BOT_SECRET', ''),
                    ],
                ],
            ],
        ]);
    }

    protected function loadDotenv(): void
    {
        $repository = RepositoryBuilder::createWithNoAdapters()
            ->addAdapter(Adapter\PutenvAdapter::class)
            ->immutable()
            ->make();

        Dotenv::create($repository, [BASE_PATH])->load();
    }
}
