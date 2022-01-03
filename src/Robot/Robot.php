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
namespace Fan\Feishu\Robot;

use Fan\Feishu\AccessToken\TenantAccessToken;
use Fan\Feishu\Http\Client;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp\RequestOptions;

class Robot implements ProviderInterface
{
    public function __construct(protected Client $client, protected TenantAccessToken $token)
    {
    }

    /**
     * 机器人信息.
     */
    public function info()
    {
        $ret = $this->client->client()->get('open-apis/bot/v3/info/', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->token->getToken(),
            ],
        ]);

        return $this->client->handleResponse($ret)['bot'];
    }

    /**
     * 群组列表.
     */
    public function groupList(int $pageSize = 10, string $pageToken = null)
    {
        $query = ['page_size' => $pageSize];
        if ($pageToken) {
            $query['page_token'] = $pageToken;
        }

        $ret = $this->client->client()->get('open-apis/chat/v4/list', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->token->getToken(),
            ],
            RequestOptions::QUERY => $query,
        ]);

        return $this->client->handleResponse($ret)['data'] ?? [];
    }

    public function getOpenId(): string
    {
        if (empty($this->openId)) {
            $info = $this->info();
            $this->openId = $info['open_id'];
        }

        return $this->openId;
    }

    public function sendText(string $chatId, string $text)
    {
        return $this->message->sendText(
            [
                'open_id' => $this->getOpenId(),
                'chat_id' => $chatId,
            ],
            $text,
            $this->getAccessToken()
        );
    }

    public static function getName(): string
    {
        return 'robot';
    }
}