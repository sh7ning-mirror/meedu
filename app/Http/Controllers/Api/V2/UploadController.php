<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\ApiV2\UploadImageRequest;

class UploadController extends BaseController
{
    public function image(UploadImageRequest $request)
    {
        $data = $request->filldata();
        return $this->data($data);
    }
}
