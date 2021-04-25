<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

return [

    // 关于我们
    'aboutus' => '',

    // 会员配置
    'member' => [
        'is_active_default' => 1,
        'is_lock_default' => 0,

        // 用户协议
        'protocol' => '',
        // 隐私协议
        'private_protocol' => '',

        // 是否提醒绑定手机号
        'enabled_mobile_bind_alert' => 0,

        // 头像
        'default_avatar' => '/images/default_avatar.jpg',

        // Socialite
        'socialite' => [
            'qq' => [
                'app' => 'qq',
                'name' => 'QQ',
                'logo' => '/images/icons/qq.svg',
                'enabled' => 0,
            ],
        ],

        // Invite
        'invite' => [
            // 免费用户是否可以邀请
            'free_user_enabled' => false,
            // 邀请用户奖励
            'invite_user_reward' => 0,
            // 被邀请用户奖励
            'invited_user_reward' => 0,
            // 上下级关系持续时间
            'effective_days' => 365,
            // 邀请余额是否可以支付
            'invite_balance_can_pay' => 1,
            // 订单抽成
            'per_order_draw' => 0.01,
        ],

        // 积分
        'credit1' => [
            // 注册送
            'register' => 0,
            // 看完课程
            'watched_course' => 0,
            // 看完视频
            'watched_video' => 0,
            // 支付订单
            'paid_order' => 0,
            // 邀请用户
            'invite' => 0,
        ],

        // 注册送VIP
        'register' => [
            'vip' => [
                // 开启[1:是,0否]
                'enabled' => 0,
                // VIP[id]
                'role_id' => 0,
                // 赠送天数
                'days' => 0,
            ]
        ],
    ],

    // 上传
    'upload' => [
        'image' => [
            'disk' => 'public',
            'path' => 'images',
            'params' => '',
        ],
        'video' => [
            'aliyun' => [
                'region' => 'cn-shanghai',
                'host' => '',
                'access_key_id' => '',
                'access_key_secret' => '',
            ],
        ],
        'attach' => [
            'course' => [
                'disk' => 'attach',
                'path' => 'course',
            ],
        ],
    ],

    // 管理员配置
    'administrator' => [
        'super_slug' => 'administrator',
    ],

    // 支付网关
    'payment' => [
        'alipay' => [
            'enabled' => 0,
            'handler' => \App\Meedu\Payment\Alipay\Alipay::class,
            'name' => '支付宝',
            'logo' => '/images/icons/alipay.png',
            'sign' => 'alipay',
            'default_method' => 'web',
            'pc' => 'web',
            'h5' => 'wap',
        ],
        'wechat' => [
            'enabled' => 0,
            'handler' => \App\Meedu\Payment\Wechat\WechatScan::class,
            'name' => '微信支付',
            'logo' => '/images/icons/wechat-pay.png',
            'sign' => 'wechat',
            'default_method' => 'scan',
            'pc' => 'scan',
            'wechat_mini' => 'miniapp',
        ],
        'wechat-jsapi' => [
            'enabled' => 1,
            'name' => '微信支付',
            'logo' => '/images/icons/wechat-pay.png',
            'sign' => 'wechat-jsapi',
            'handler' => \App\Meedu\Payment\Wechat\WechatJSAPI::class,
            'wechat' => 'mp',
        ],
        'handPay' => [
            'enabled' => 0,
            'handler' => \App\Meedu\Payment\HandPay\HandPay::class,
            'name' => '手动打款',
            'logo' => '/images/icons/handpay.png',
            'sign' => 'handPay',
            'default_method' => 'hand',
            'pc' => 'hand',
            'h5' => 'hand',
            'wechat' => 'hand',
        ],
    ],

    // SEO
    'seo' => [
        'index' => [
            'title' => 'MeEdu',
            'keywords' => '',
            'description' => 'MeEdu是一套开源的，免费的在线视频点播系统。',
        ],
        'course_list' => [
            'title' => '所有课程',
            'keywords' => '',
            'description' => 'MeEdu是一套开源的，免费的在线视频点播系统。',
        ],
        'role_list' => [
            'title' => 'VIP',
            'keywords' => '',
            'description' => 'MeEdu是一套开源的，免费的在线视频点播系统。',
        ],
    ],

    // 系统配置
    'system' => [
        // ICP备案
        'icp' => '',
        'icp_link' => '',
        // 公安网备案
        'icp2' => '',
        'icp2_link' => '',
        'logo' => '/images/logo.png',
        'white_logo' => '/images/white-logo.png',
        'player_thumb' => '/images/player-thumb.png',

        // 播放器
        'player' => [
            // 跑马灯
            'enabled_bullet_secret' => 0,
            // 阿里云私密播放
            'enabled_aliyun_private' => 0,
            // 腾讯云播放key
            'tencent_play_key' => '',
            // 腾讯云超级播放器配置
            'tencent_pcfg' => 'default',
        ],

        // 缓存开关
        'cache' => [
            'status' => 0,
            'expire' => 3600 * 10,
        ],
        // 短信频率
        'limiter' => [
            'sms' => [
                'times' => 6,
                'minutes' => 1,
            ]
        ],
        // 统计代码
        'js' => '',
        // 主题
        'theme' => [
            'use' => 'default',
            'path' => base_path(env('TEMPLATE_PATH') ?: 'resources/views'),
        ],
        // 默认短信服务商
        'sms' => 'aliyun',
        // editor
        'editor' => \App\Constant\FrontendConstant::RENDER_MARKDOWN,
        // 登录
        'login' => [
            'limit' => [
                'rule' => \App\Constant\FrontendConstant::LOGIN_LIMIT_RULE_DEFAULT,
            ]
        ],
        // 全局css
        'css' => [
            'pc' => '',
            'h5' => '',
        ],
    ],

    // 其它配置
    'other' => [
        // 课程列表页展示条数
        'course_list_page_size' => 16,
        // 视频列表页展示条数
        'video_list_page_size' => 16,
    ],

    // MeEduCloud
    'meeducloud' => [
        'domain' => env('MEEDUCLOUD_DOMAIN', 'https://meedu.vip'),
        'user_id' => env('MEEDUCLOUD_USER_ID'),
        'password' => env('MEEDUCLOUD_PASSWORD'),
    ],

    // 第三方服务
    'services' => [
        // 高德地图配置（用于ip解析）
        'amap' => [
            // @see https://lbs.amap.com/api/webservice/guide/create-project/get-key
            'key' => '',
        ],
    ],

    // 微信公众号配置
    'mp_wechat' => [
        'app_id' => '',
        'app_secret' => '',
        'token' => '',
        'aes_key' => '',
        // 开启授权登录
        'enabled_oauth_login' => 0,
        // 开启PC扫码登录
        'enabled_scan_login' => 0,
        // 开启微信浏览器的分享
        'enabled_share' => 0,
        // 微信H5分享自定义内容
        'share' => [
            // 分享标题
            'title' => '',
            // 分享描述
            'desc' => '',
            // 分享的图片
            'imgUrl' => '',
        ],
    ],
];
