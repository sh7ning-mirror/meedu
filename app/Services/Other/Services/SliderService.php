<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace App\Services\Other\Services;

use App\Services\Other\Models\Slider;
use App\Services\Other\Interfaces\SliderServiceInterface;

class SliderService implements SliderServiceInterface
{
    /**
     * @param string $platform
     * @return array
     */
    public function all($platform = ''): array
    {
        return Slider::query()
            ->when($platform, function ($query) use ($platform) {
                $query->where('platform', $platform);
            })
            ->orderBy('sort')
            ->get()
            ->toArray();
    }
}
