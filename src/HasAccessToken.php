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

use Fan\Feishu\Exception\TokenInvalidException;
use Psr\Http\Message\ResponseInterface;

trait HasAccessToken
{
    private function __request(string $method, string $uri, array $options = []): array
    {
        $client = $this->client->client($this->token);

        $response = $client->request($method, $uri, $options);

        return $this->client->handleResponse($response);
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            return $this->__request($method, $uri, $options);
        } catch (TokenInvalidException) {
            $this->token->getToken(true);
            return $this->__request($method, $uri, $options);
        }
    }

    public function handleResponse(ResponseInterface $response): array
    {
        return $this->client->handleResponse($response);
    }
}
