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
    'http' => [
        'base_uri' => 'https://open.feishu.cn',
        'http_errors' => false,
        'timeout' => 2,
    ],
    'applications' => [
        'default' => [
            'app_id' => env('FEISHU_APPID', ''),
            'app_secret' => env('FEISHU_SECRET', ''),
        ],
    ],
];
