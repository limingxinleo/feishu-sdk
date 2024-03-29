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
use Fan\Feishu\HasAccessToken;
use Fan\Feishu\Http\Client;
use Fan\Feishu\Message\Message;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp\RequestOptions;

class Robot implements ProviderInterface
{
    use HasAccessToken;

    private ?string $openId = null;

    public function __construct(protected Client $client, protected TenantAccessToken $token, protected Message $message)
    {
    }

    /**
     * 机器人信息.
     */
    public function info()
    {
        $ret = $this->request('GET', 'open-apis/bot/v3/info/');

        return $ret['bot'];
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

        $ret = $this->request('GET', 'open-apis/chat/v4/list', [
            RequestOptions::QUERY => $query,
        ]);

        return $ret['data'] ?? [];
    }

    public function getOpenId(): string
    {
        if (empty($this->openId)) {
            $info = $this->info();
            $this->openId = $info['open_id'];
        }

        return $this->openId;
    }

    /**
     * @param $data = [
     *     'receive_id' => 'oc_5ad11d72b830411d72b836c20',
     *     'msg_type' => 'text',
     *     'content' => [
     *         'text' => '',
     *     ],
     * ]
     */
    public function send(array $data, string $type = 'chat_id')
    {
        return $this->message->send($data, $type);
    }

    /**
     * @deprecated
     */
    public function sendText(string $chatId, string $text)
    {
        return $this->message->sendText(
            [
                'chat_id' => $chatId,
            ],
            $text,
        );
    }

    public static function getName(): string
    {
        return 'robot';
    }
}
