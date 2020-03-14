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
use Hyperf\Guzzle\HandlerStackFactory as HyperfHandlerStackFactory;
use Psr\Container\ContainerInterface;

class HandlerStackFactory
{
    /**
     * @var callable[]|HandlerStack[]
     */
    protected $stacks = [];

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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
            $this->stacks[$name] = $this->getDefaultHandlerStack();
        }

        return $this->stacks[$name];
    }

    public function getDefaultHandlerStack(): HandlerStack
    {
        return $this->container->get(HyperfHandlerStackFactory::class)->create();
    }
}
