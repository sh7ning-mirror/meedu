<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Listeners\VodCourseDestroyedEvent;

use App\Meedu\Search\VodSearchNotify;
use App\Events\VodCourseDestroyedEvent;

class SearchRecordNotify
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param VodCourseDestroyedEvent $event
     * @return void
     */
    public function handle(VodCourseDestroyedEvent $event)
    {
        app()->make(VodSearchNotify::class)->delete($event->id);
    }
}
