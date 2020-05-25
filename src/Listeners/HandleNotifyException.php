<?php

namespace Someshwer\Firewall\src\Listeners;

use Illuminate\Support\Facades\Mail;
use Someshwer\Firewall\src\Events\NotifyException;

class HandleNotifyException
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotifyException $event
     *
     * @return void
     */
    public function handle(NotifyException $event)
    {
        $exception = $event->exception_data;
        Mail::send(
            'package_redirect::exception_notification_view',
            ['exception' => $exception],
            function ($message) {
                $from = config('firewall.notify_exceptions.mail_from');
                $to = config('firewall.notify_exceptions.mail_to');
                $subject = config('firewall.notify_exceptions.subject');
                $message->from($from, $subject);
                $message->to($to);
            }
        );
    }
}
