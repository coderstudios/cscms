<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */
 
namespace CoderStudios\CSCMS\Middleware;

use Auth;
use Closure;
use CoderStudios\CSCMS\Library\Notifications as NotificationsLibrary;
use CoderStudios\CSCMS\Library\NotificationsRead;

class Notifications
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(NotificationsLibrary $notifications, NotificationsRead $notifications_read)
    {
        $this->notifications = $notifications;
        $this->notifications_read = $notifications_read;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {
        $notifications_data = [];
        if (Auth::user()) {
    		$notifications = $this->notifications->getAll();
            if ($notifications->count()) {
                foreach($notifications as $notification) {
                    if (!$this->notifications_read->hasSeen(Auth::user()->id,$notification->id)) {
                        $notifications_data[] = $notification;
                        $this->notifications_read->create([
                            'read' => 1,
                            'user_id' => Auth::user()->id,
                            'notification_id' => $notification->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }

        $request->session()->put('notifications',$notifications_data);
        return $next($request);
    }
}