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

use Fan\Feishu\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * @property Provider\Robots $robots
 * @property Provider\Message $message
 */
class Application
{
    protected array $alias = [
        'robots' => Provider\Robots::class,
        'message' => Provider\Message::class,
    ];

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function __get($name)
    {
        if (! isset($name)) {
            throw new InvalidArgumentException("{$name} is invalid.");
        }

        return $this->container->get(
            $this->alias[$name]
        );
    }
}
