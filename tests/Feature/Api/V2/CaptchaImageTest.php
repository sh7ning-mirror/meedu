<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace Tests\Feature\Api\V2;

class CaptchaImageTest extends Base
{
    public function test_captchaImage()
    {
        $this->get('/api/v2/captcha/image')
            ->seeStatusCode(200);
    }
}
