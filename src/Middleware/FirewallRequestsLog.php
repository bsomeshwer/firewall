<?php namespace Someshwer\Firewall\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
        $firewall_requests_log = new FirewallRequestsLogModel();
        if ($this->log_request == true) {
            $firewall_requests_log = $this->prepareAndSaveLogData($request, $firewall_requests_log);
        }
        $response = $next($request);
        $this->logResponseData($response, $firewall_requests_log);
        return $response;
    }

    /**
     * @param $request
     * @param $firewall_requests_log
     * @return FirewallRequestsLogModel
     *
     * Prepares request data to be stored in a table.
     * And stores prepared data in a table.
     */
    private function prepareAndSaveLogData($request, $firewall_requests_log)
    {
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

    private function prepareResponseData()
    {

    }

    /**
     * This logs and stores the response data to firewall_requests_log table.
     *
     * @param $response
     * @param $firewall_requests_log
     * @return mixed
     */
    private function logResponseData($response, $firewall_requests_log)
    {
        $firewall_requests_log->response_data = ($response instanceof JsonResponse) ? $response : null;
        $firewall_requests_log->save();
        return $firewall_requests_log;
    }

}
