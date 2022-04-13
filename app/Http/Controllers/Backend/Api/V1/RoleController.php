<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Http\Controllers\Backend\Api\V1;

use App\Services\Member\Models\Role;
use App\Services\Member\Models\User;
use App\Http\Requests\Backend\RoleRequest;

class RoleController extends BaseController
{
    public function all()
    {
        $roles = Role::query()->get();
        return $this->successData(['data' => $roles]);
    }

    public function index()
    {
        $roles = Role::orderByDesc('id')->paginate(request()->input('size', 12));

        return $this->successData($roles);
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->filldata());

        return $this->success();
    }

    public function edit($id)
    {
        $info = Role::findOrFail($id);

        return $this->successData($info);
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->fill($request->filldata())->save();

        return $this->success();
    }

    public function destroy($id)
    {
        if (User::query()->where('role_id', $id)->exists()) {
            return $this->error(__('当前VIP下存在用户无法删除'));
        }

        Role::destroy($id);

        return $this->success();
    }
}
