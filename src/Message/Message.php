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
namespace Fan\Feishu\Message;

use Fan\Feishu\AccessToken\TenantAccessToken;
use Fan\Feishu\Http\Client;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp\RequestOptions;

class Message implements ProviderInterface
{
    public function __construct(protected Client $client, protected TenantAccessToken $token)
    {
    }

    /**
     * @param $data = [
     *     'open_id' => 'ou_5ad573a6411d72b8305fda3a9c15c70e',
     *     'root_id' => 'om_40eb06e7b84dc71c03e009ad3c754195',
     *     'chat_id' => 'oc_5ad11d72b830411d72b836c20',
     *     'user_id' => '92e39a99',
     *     'email' => 'fanlv@gmail.com',
     *     'msg_type' => 'text',
     *     'content' => [
     *         'text' => '',
     *     ],
     * ]
     */
    public function send(array $data)
    {
        $ret = $this->client->client()->post('open-apis/message/v4/send/', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->token->getToken()],
            RequestOptions::JSON => $data,
        ]);

        return $this->client->handleResponse($ret)['data'] ?? [];
    }

    /**
     * @see https://open.feishu.cn/document/ukTMukTMukTM/uUjNz4SN2MjL1YzM
     * @param $data = [
     *     'open_id' => 'ou_5ad573a6411d72b8305fda3a9c15c70e',
     *     'root_id' => 'om_40eb06e7b84dc71c03e009ad3c754195',
     *     'chat_id' => 'oc_5ad11d72b830411d72b836c20',
     *     'user_id' => '92e39a99',
     *     'email' => 'fanlv@gmail.com',
     * ]
     */
    public function sendText(array $data, string $text)
    {
        $data['msg_type'] = 'text';
        $data['content']['text'] = $text;

        return $this->send($data);
    }

    public static function getName(): string
    {
        return 'message';
    }
}
