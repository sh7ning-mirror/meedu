<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Listeners\PaymentSuccessEvent;

use App\Businesses\BusinessState;
use App\Events\PaymentSuccessEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Base\Services\ConfigService;
use App\Services\Member\Services\UserService;
use App\Services\Member\Services\CreditService;
use App\Services\Order\Services\PromoCodeService;
use App\Services\Member\Services\NotificationService;
use App\Services\Base\Interfaces\ConfigServiceInterface;
use App\Services\Member\Interfaces\UserServiceInterface;
use App\Services\Member\Interfaces\CreditServiceInterface;
use App\Services\Order\Interfaces\PromoCodeServiceInterface;
use App\Services\Member\Interfaces\NotificationServiceInterface;

class PromoCodeListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var PromoCodeService
     */
    protected $promoCodeService;
    protected $businessState;
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ConfigService
     */
    protected $configService;

    /**
     * @var CreditService
     */
    protected $creditService;

    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * PromoCodeListener constructor.
     * @param PromoCodeServiceInterface $promoCodeService
     * @param BusinessState $businessState
     * @param UserServiceInterface $userService
     * @param ConfigServiceInterface $configService
     * @param CreditServiceInterface $creditService
     * @param NotificationServiceInterface $notificationService
     */
    public function __construct(
        PromoCodeServiceInterface $promoCodeService,
        BusinessState $businessState,
        UserServiceInterface $userService,
        ConfigServiceInterface $configService,
        CreditServiceInterface $creditService,
        NotificationServiceInterface $notificationService
    ) {
        $this->promoCodeService = $promoCodeService;
        $this->businessState = $businessState;
        $this->userService = $userService;
        $this->configService = $configService;
        $this->creditService = $creditService;
        $this->notificationService = $notificationService;
    }

    /**
     * @param PaymentSuccessEvent $event
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(PaymentSuccessEvent $event)
    {
        $order = $event->order;
        $promoCodeOrderPaidRecords = $this->promoCodeService->getOrderPaidRecords($order['id']);
        if (!$promoCodeOrderPaidRecords) {
            return;
        }
        $promoCodes = $this->promoCodeService->getList(array_column($promoCodeOrderPaidRecords, 'paid_type_id'));
        if (!$promoCodes) {
            return;
        }
        $code = [];
        foreach ($promoCodes as $promoCode) {
            if ($this->businessState->isUserInvitePromoCode($promoCode['code'])) {
                $code = $promoCode;
                break;
            }
        }
        if (!$code) {
            return;
        }

        $orderUser = $this->userService->find($order['user_id']);
        if ($code['user_id'] && $orderUser['invite_user_id'] === 0) {
            // 当前用户使用了优惠码，且没有上级
            // 那么将该优惠码的所属用户设置为当前用户的上级
            $this->userService->updateInviteUserId($orderUser['id'], $code['user_id'], $code['invite_user_reward']);

            if ($credit1 = $this->configService->getInviteSceneCredit1()) {
                $message = sprintf(__('邀请用户注册送%d积分'), $credit1);
                // 积分奖励
                $this->creditService->createCredit1Record($code['user_id'], $credit1, $message);
                // 积分到账通知
                $this->notificationService->notifyCredit1Message($code['user_id'], $credit1, $message);
            }
        }

        // 记录用户使用invite_promo_code的状态
        // 每个用户只能只能使用一次其它用户的邀请码
        if ((int)$orderUser['is_used_promo_code'] !== 1) {
            $this->userService->setUsedPromoCode($orderUser['id']);
        }
    }
}
