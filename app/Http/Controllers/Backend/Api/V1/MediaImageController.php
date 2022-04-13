<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Http\Controllers\Backend\Api\V1;

use App\Models\MediaImage;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ImageUploadRequest;

class MediaImageController extends BaseController
{
    public function index(Request $request)
    {
        $from = (int)$request->input('from');

        $images = MediaImage::query()
            ->select([
                'id', 'from', 'url', 'path', 'disk', 'name', 'created_at',
            ])
            ->when($from, function ($query) use ($from) {
                $query->where('from', $from);
            })
            ->orderByDesc('id')
            ->paginate($request->input('size'));

        return $this->successData([
            'data' => [
                'data' => $images->items(),
                'total' => $images->total(),
            ],
            'from' => [
                [
                    'name' => __('全部'),
                    'key' => 0,
                ],
                [
                    'name' => __('编辑器'),
                    'key' => 1,
                ],
                [
                    'name' => __('直接上传'),
                    'key' => 2,
                ],
            ],
        ]);
    }

    public function upload(ImageUploadRequest $request)
    {
        $file = $request->filldata();
        $data = save_image($file);
        $from = (int)$request->input('from');

        // 创建记录
        MediaImage::create([
            'from' => $from,
            'name' => $data['name'],
            'url' => $data['url'],
            'path' => $data['path'],
            'disk' => $data['disk'],
        ]);

        return $this->successData($data);
    }
}
