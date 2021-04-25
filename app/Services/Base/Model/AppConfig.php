<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace App\Services\Base\Model;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $table = 'app_config';

    protected $fillable = [
        'group', 'name', 'field_type', 'sort', 'default_value', 'key', 'value', 'is_private',
        'option_value', 'help', 'is_show',
    ];
}
