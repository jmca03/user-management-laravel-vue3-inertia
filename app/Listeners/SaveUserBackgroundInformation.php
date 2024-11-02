<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveUserBackgroundInformation
{
    /**
     * Create the event listener.
     *
     * @param \App\Services\UserService $userService
     */
    public function __construct(protected UserService $userService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $this->userService->saveDetails($event->user);
    }
}
