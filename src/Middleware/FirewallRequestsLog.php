<?php namespace Someshwer\Firewall\Middleware;

use Closure;
use Someshwer\Firewall\src\Entities\FirewallRequestsLogModel;

/**
 * Class FirewallRequestsLog
 * @package Someshwer\Firewall\Middleware
 *
 * @author Someshwer Bandapally
 * Date: 14-08-2018
 *
 * Each and every incoming request is logged and stored into database.
 */
class FirewallRequestsLog
{

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $log_request;

    /**
     * FirewallRequestsLog constructor.
     */
    public function __construct()
    {
        $this->log_request = config('firewall.log_request');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->log_request == true) {
            $this->prepareAndSaveLogData($request);
        }
        return $next($request);
    }

    /**
     * @param $request
     * @return FirewallRequestsLogModel
     *
     * Prepares request data to be stored in a table.
     * And stores prepared data in a table.
     */
    private function prepareAndSaveLogData($request)
    {
        $firewall_requests_log = new FirewallRequestsLogModel();
        $firewall_requests_log->fill([
            'path' => $request->path(),
            'url' => $request->url(),
            'full_url' => $request->fullUrl(),
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REQUEST_URI'],
            'query' => $request->query() ? $request->query() : null,
            'file_name' => $_SERVER['SCRIPT_FILENAME'],
            'http_host' => $_SERVER['HTTP_HOST'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'ip_address' => $request->ip(),
            'all_request_data' => $_SERVER
        ]);
        $firewall_requests_log->save();
        return $firewall_requests_log;
    }
}
