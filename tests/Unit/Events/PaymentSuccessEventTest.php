<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace Tests\Unit\Events;

use Carbon\Carbon;
use Tests\TestCase;
use App\Events\PaymentSuccessEvent;
use App\Services\Member\Models\User;
use App\Services\Order\Models\Order;

class PaymentSuccessEventTest extends TestCase
{
    public function test_InviteUserRewardListener()
    {
        config(['meedu.member.invite.per_order_draw' => 0.02]);

        $user = User::factory()->create();
        $user1 = User::factory()->create([
            'invite_user_id' => $user->id,
            'invite_user_expired_at' => Carbon::now()->addDays(1),
        ]);

        $order = Order::factory()->create(['user_id' => $user1->id, 'charge' => 100]);

        event(new PaymentSuccessEvent($order->toArray()));

        $user->refresh();
        $this->assertEquals(2, $user->invite_balance);
    }
}
