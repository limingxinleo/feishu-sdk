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

return [
    'guzzle' => [
        'config' => [
            'base_uri' => 'https://open.feishu.cn',
            'timeout' => 2,
        ],
    ],
    'robots' => [
        'default' => [
            'app_id' => '',
            'secret' => '',
        ],
    ],
];
