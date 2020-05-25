<?php

namespace Someshwer\Firewall\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Someshwer\Firewall\src\Entities\FirewallRequestsLogModel;

/**
 * Class FirewallRequestsLog.
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
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $firewall_requests_log = new FirewallRequestsLogModel();
        if ($this->log_request == true) {
            $firewall_requests_log = $this->prepareAndSaveLogData($request, $firewall_requests_log);
        }
        $response = $next($request);
        $response_data = $this->prepareResponseData($response);
        $this->logResponseData($response_data, $firewall_requests_log);

        return $response;
    }

    /**
     * @param $request
     * @param $firewall_requests_log
     *
     * @return FirewallRequestsLogModel
     *
     * Prepares request data to be stored in a table.
     * And stores prepared data in a table.
     */
    private function prepareAndSaveLogData($request, $firewall_requests_log)
    {
        $firewall_requests_log->fill([
            'path'             => $request->path(),
            'url'              => $request->url(),
            'full_url'         => $request->fullUrl(),
            'method'           => $_SERVER['REQUEST_METHOD'],
            'uri'              => $_SERVER['REQUEST_URI'],
            'query'            => $request->query() ? $request->query() : null,
            'file_name'        => $_SERVER['SCRIPT_FILENAME'],
            'http_host'        => $_SERVER['HTTP_HOST'],
            'http_user_agent'  => $_SERVER['HTTP_USER_AGENT'],
            'ip_address'       => $request->ip(),
            'all_request_data' => $_SERVER,
        ]);
        $firewall_requests_log->save();

        return $firewall_requests_log;
    }

    /**
     * Preparing response data to be stored.
     *
     * @param $response
     *
     * @return array
     */
    private function prepareResponseData($response)
    {
        try {
            $response_data = [
                'status_code' => $response->getStatusCode(),
                'headers'     => [
                    'cache_control' => $response->headers->get('cache-control'),
                    'content_type'  => $response->headers->get('content-type'),
                    'date'          => $response->headers->get('date'),
                ],
                // 'original_data'=>$response->getOriginalContent(),
            ];
        } catch (\Exception $e) {
            Log::error($e);
            $response_data = null;
        }

        return $response_data;
    }

    /**
     * This logs and stores the response data to firewall_requests_log table.
     *
     * @param $response
     * @param $firewall_requests_log
     *
     * @return mixed
     */
    private function logResponseData($response, $firewall_requests_log)
    {
        $firewall_requests_log->response_data = $response;
        $firewall_requests_log->save();

        return $firewall_requests_log;
    }
}
