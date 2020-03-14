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

namespace HyperfX\Feishu\Provider;

use GuzzleHttp\RequestOptions;
use HyperfX\Feishu\AbstractProvider;

class Message extends AbstractProvider
{
    protected $name = 'Message';

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
    public function send(array $data, string $token)
    {
        $ret = $this->client()->post('/open-apis/message/v4/send/', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $token],
            RequestOptions::JSON => $data,
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
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
    public function sendText(array $data, string $text, string $token)
    {
        $data['msg_type'] = 'text';
        $data['content']['text'] = $text;

        return $this->send($data, $token);
    }
}
