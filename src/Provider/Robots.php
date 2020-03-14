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

use HyperfX\Feishu\AbstractProvider;
use HyperfX\Feishu\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

class Robots extends AbstractProvider
{
    /**
     * @var Robot[]
     */
    protected $robots;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        foreach ($this->config->get('feishu.robots', []) as $key => $item) {
            $this->robots[$key] = make(Robot::class, [
                'conf' => $item,
            ]);
        }
    }

    public function __get($name)
    {
        if (! isset($this->robots[$name]) || ! $this->robots[$name] instanceof Robot) {
            throw new InvalidArgumentException("Robot {$name} is invalid.");
        }

        return $this->robots[$name];
    }
}
