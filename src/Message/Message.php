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
use Fan\Feishu\Exception\InvalidArgumentException;
use Fan\Feishu\HasAccessToken;
use Fan\Feishu\Http\Client;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp\RequestOptions;
use Hyperf\Utils\Codec\Json;

class Message implements ProviderInterface
{
    use HasAccessToken;

    public function __construct(protected Client $client, protected TenantAccessToken $token)
    {
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
        $data['content'] = Json::encode($data['content']);
        $ret = $this->request('POST', 'open-apis/im/v1/messages', [
            RequestOptions::JSON => $data,
            RequestOptions::QUERY => [
                'receive_id_type' => $type,
            ],
        ]);

        return $ret['data'] ?? [];
    }

    /**
     * @deprecated
     * @see https://open.feishu.cn/document/ukTMukTMukTM/uUjNz4SN2MjL1YzM
     * @param $data = [
     *     'open_id' => 'ou_5ad573a6411d72b8305fda3a9c15c70e',
     *     'chat_id' => 'oc_5ad11d72b830411d72b836c20',
     *     'user_id' => '92e39a99',
     *     'email' => 'fanlv@gmail.com',
     * ]
     */
    public function sendText(array $data, string $text)
    {
        $data['msg_type'] = 'text';
        $data['content']['text'] = $text;

        $type = null;
        foreach (['chat_id', 'open_id', 'user_id', 'email'] as $key) {
            if (isset($data[$key])) {
                $type = $key;
                $data['receive_id'] = $data[$key];
                unset($data[$key]);
                break;
            }
        }

        if (! isset($type)) {
            throw new InvalidArgumentException("Couldn't guess the type for message request.");
        }

        return $this->send($data, $type);
    }

    public static function getName(): string
    {
        return 'message';
    }
}
