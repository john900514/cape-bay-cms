<?php

namespace AnchorCMS\Jobs\User;

use AnchorCMS\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OnboardNewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $new_user, $creator;
    /**
     * Create a new job instance.
     * @param $new_user
     * @param $creating_user
     * @return void
     */
    public function __construct(User $new_user, User $creating_user)
    {
        $this->new_user = $new_user;
        $this->creator = $creating_user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Steps
         * 1. Prepare and fire NewUser Email with Completion Link
         * @todo - create subscription pipeline
         * @todo - create notification table and model
         * 2. Fire New User Created Notification to Host Client Admins
         * 3. If assigned client is not Host Client then
         *      Fire New User Created Notification to Host Client Admins
         */
    }
}
