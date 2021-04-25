<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace Tests\Commands;

use Tests\OriginalTestCase;

class MemberRoleExpiredHandlerCommandTest extends OriginalTestCase
{
    public function test_run()
    {
        $this->artisan('member:role:expired')->assertExitCode(0);
    }
}
