<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace App\Services\Other\Services;

use App\Services\Other\Models\IndexBanner;
use App\Services\Other\Interfaces\IndexBannerServiceInterface;

class IndexBannerService implements IndexBannerServiceInterface
{
    public function all(): array
    {
        return IndexBanner::query()->orderBy('sort')->get()->toArray();
    }
}
