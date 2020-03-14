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

namespace HyperfX\Feishu;

use GuzzleHttp\HandlerStack;
use HyperfX\Feishu\Exception\InvalidArgumentException;

class HandlerStackFactory
{
    /**
     * @var callable[]|HandlerStack[]
     */
    protected $stacks = [];

    public function set(string $name, $handler)
    {
        $this->stacks[$name] = $handler;
    }

    /**
     * @return callable|HandlerStack
     */
    public function get(string $name)
    {
        if (! isset($this->stacks[$name])) {
            throw new InvalidArgumentException("Handler {$name} is invalid.");
        }

        return $this->stacks[$name];
    }
}
