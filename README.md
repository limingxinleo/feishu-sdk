# 飞书SDK

[![Build Status](https://travis-ci.org/limingxinleo/feishu-sdk.svg?branch=master)](https://travis-ci.org/limingxinleo/feishu-sdk)

```
composer require limingxinleo/feishu
```

## 使用

### 快速开始

`Hyperf` 框架中，可以直接使用 `Fan\Feishu\Factory`。

1. 发布配置

```shell
php bin/hyperf.php vendor:publish limingxinleo/feishu
```

2. 注入并使用

```php
<?php

use Fan\Feishu\Factory;
use Hyperf\Di\Annotation\Inject;

class IndexController
{
    #[Inject]
    public Factory $factory;
    
    public function index()
    {
        return $this->factory->get('default')->contact->batchGetUserId(emails: ['l@hyperf.io']);
    }
}
```

其他框架，可以自行 `new Application()` 使用。

```php
<?php

use Fan\Feishu\Application;

$app = new Application([
    'app_id' => 'xxx',
    'app_secret' => 'xxx',
    'http' => [
        'base_uri' => 'https://open.feishu.cn',
        'http_errors' => false,
        'timeout' => 2,
    ],
]);

$result = $app->contact->batchGetUserId(emails: ['l@hyperf.io']);
```
