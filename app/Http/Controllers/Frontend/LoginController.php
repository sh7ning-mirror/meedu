<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace App\Http\Controllers\Frontend;

use QrCode;
use App\Bus\AuthBus;
use App\Meedu\Wechat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constant\CacheConstant;
use App\Businesses\BusinessState;
use App\Exceptions\ServiceException;
use App\Http\Controllers\BaseController;
use Laravel\Socialite\Facades\Socialite;
use App\Services\Base\Services\CacheService;
use App\Services\Member\Services\UserService;
use App\Services\Member\Services\SocialiteService;
use App\Http\Requests\Frontend\LoginPasswordRequest;
use App\Services\Base\Interfaces\CacheServiceInterface;
use App\Services\Member\Interfaces\UserServiceInterface;
use App\Services\Member\Interfaces\SocialiteServiceInterface;

class LoginController extends BaseController
{

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var SocialiteService
     */
    protected $socialiteService;

    public function __construct(
        UserServiceInterface $userService,
        SocialiteServiceInterface $socialiteService
    ) {
        parent::__construct();

        $this->userService = $userService;
        $this->socialiteService = $socialiteService;

        $this->middleware('guest');
    }

    // 登录界面
    public function showLoginPage()
    {
        $this->recordRedirectTo();

        $title = __('title.login');

        return v('frontend.auth.login', compact('title'));
    }

    // 密码登录
    public function passwordLoginHandler(LoginPasswordRequest $request, AuthBus $bus)
    {
        [
            'mobile' => $mobile,
            'password' => $password,
        ] = $request->filldata();

        $user = $this->userService->passwordLogin($mobile, $password);

        if (!$user) {
            throw new ServiceException(__('mobile not exists or password error'));
        }

        if ((int)$user['is_lock'] === 1) {
            throw new ServiceException(__('current user was locked,please contact administrator'));
        }

        $bus->webLogin($user['id'], $request->has('remember'), $this->userPlatform());

        return redirect($this->redirectTo());
    }

    // 社交登录
    public function socialLogin($app)
    {
        $this->recordRedirectTo();

        $enabledSocialites = $this->configService->getEnabledSocialiteApps();
        if (!in_array($app, array_column($enabledSocialites, 'app'))) {
            return $this->error(__('登录方式不存在'));
        }

        // 修改回调地址
        config(['services.' . $app . '.redirect' => route('socialite.callback', [$app])]);

        return Socialite::driver($app)->redirect();
    }

    // 社交登录回调
    public function socialiteLoginCallback(AuthBus $bus, $app)
    {
        $user = Socialite::driver($app)->user();
        $appId = $user->getId();

        $userId = $this->socialiteService->getBindUserId($app, $appId);

        if (!$userId) {
            $userId = $this->socialiteService->bindAppWithNewUser($app, $appId, (array)$user);
        }

        // 用户是否锁定检测
        $user = $this->userService->find($userId);

        if ((int)$user['is_lock'] === 1) {
            flash(__('current user was locked,please contact administrator'));
            return redirect(url('/'));
        }

        $bus->webLogin($userId, 1, $this->userPlatform());

        return redirect($this->redirectTo());
    }

    // 微信公众号授权登录
    public function wechatLogin()
    {
        $this->recordRedirectTo();

        return Wechat::getInstance()->oauth->redirect();
    }

    // 微信公众号授权登录回调
    public function wechatLoginCallback(AuthBus $authBus)
    {
        $user = Wechat::getInstance()->oauth->user();
        if (!$user) {
            abort(500);
        }

        $originalData = $user['original'];

        $openid = $originalData['openid'];
        $unionId = $originalData['unionid'] ?? '';

        $userId = $authBus->wechatLogin($openid, $unionId, $originalData);

        $user = $this->userService->find($userId);
        if ((int)$user['is_lock'] === 1) {
            flash(__('current user was locked,please contact administrator'));
            return redirect(url('/'));
        }

        $authBus->webLogin($userId, 1, $this->userPlatform());

        return redirect($this->redirectTo());
    }

    // 微信公众号扫码登录
    public function wechatScanLogin(BusinessState $businessState)
    {
        if (!$businessState->enabledMpScanLogin()) {
            throw new ServiceException(__('未开启微信公众号扫码登录'));
        }

        $this->recordRedirectTo();

        // 获取登录的二维码url
        $code = Str::random(10);

        $result = Wechat::getInstance()->qrcode->temporary($code, 3600);
        $url = $result['url'] ?? '';
        $image = 'data:image/png;base64, ' . base64_encode(QrCode::format('png')->size(300)->generate($url));

        $title = __('微信公众号扫码登录');

        return v('frontend.auth.wechat_scan_login', compact('title', 'code', 'image'));
    }

    public function wechatScanLoginQuery(Request $request, AuthBus $authBus)
    {
        $code = $request->input('code');
        if (!$code) {
            return $this->error(__('params error'));
        }

        /**
         * @var CacheService $cacheService
         */
        $cacheService = app()->make(CacheServiceInterface::class);

        $cacheKey = get_cache_key(CacheConstant::WECHAT_SCAN_LOGIN['name'], $code);
        $userId = (int)$cacheService->pull($cacheKey);

        if (!$userId) {
            return $this->data(['status' => 0]);
        }

        $user = $this->userService->find($userId);
        if ((int)$user['is_lock'] === 1) {
            return $this->error(__('current user was locked,please contact administrator'));
        }

        $authBus->webLogin($userId, 1, $this->userPlatform());

        return $this->data([
            'status' => 1,
            'redirect_url' => $this->redirectTo(),
        ]);
    }
}
