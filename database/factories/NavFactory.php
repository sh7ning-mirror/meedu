<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

use Faker\Generator as Faker;

$factory->define(\App\Services\Other\Models\Nav::class, function (Faker $faker) {
    return [
        'sort' => $faker->numberBetween(0, 100),
        'name' => $faker->name,
        'url' => $faker->url,
        'platform' => $faker->randomElement([\App\Constant\FrontendConstant::NAV_PLATFORM_H5, \App\Constant\FrontendConstant::NAV_PLATFORM_PC]),
        'parent_id' => 0,
    ];
});
