<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

return [
    'vod' => [
        'app_id' => '',
        'secret_id' => '',
        'secret_key' => '',
    ],
    'wechat' => [
        'mini' => [
            'app_id' => env('WECHAT_MINI_APP_ID', ''),
            'secret' => env('WECHAT_MINI_APP_SECRET', ''),
        ],
    ],
];
