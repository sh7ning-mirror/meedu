<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Http\Controllers\Backend\Api\V1;

use Illuminate\Http\Request;
use App\Services\Course\Models\Course;
use Illuminate\Support\Facades\Storage;
use App\Services\Course\Models\CourseAttach;
use App\Http\Requests\Backend\CourseAttachRequest;

class CourseAttachController extends BaseController
{
    public function index(Request $request)
    {
        $courseId = $request->input('course_id');
        $course = Course::query()->where('id', $courseId)->firstOrFail();
        $attach = CourseAttach::query()->where('course_id', $courseId)->get();
        return $this->successData([
            'data' => $attach,
            'course' => $course,
        ]);
    }

    public function store(CourseAttachRequest $request)
    {
        $data = $request->filldata();
        CourseAttach::create($data);
        return $this->success();
    }

    public function destroy($id)
    {
        $attach = CourseAttach::query()->where('id', $id)->firstOrFail();

        // 删除附件
        Storage::disk(config('meedu.upload.attach.course.disk'))->delete($attach['path']);

        // 删除数据库记录
        $attach->delete();

        return $this->success();
    }
}
