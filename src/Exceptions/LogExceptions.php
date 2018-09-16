<?php namespace Someshwer\Firewall\src\Exceptions;

use Illuminate\Database\QueryException;
use Someshwer\Firewall\src\Entities\ExceptionLog;

/**
 * Class LogExceptions
 * @package Someshwer\Firewall\src\Exceptions
 *
 * @author Someshwer
 * Date: 16-09-2018
 */
class LogExceptions
{
    /**
     * @var $request
     */
    private $request;

    /**
     * @var $exception
     */
    private $exception;

    /**
     * LogExceptions constructor.
     * @param $req
     * @param $e
     */
    public function __construct($req, $e)
    {
        $this->request = $req;
        $this->exception = $e;
        $this->logException($req, $e);
    }

    /**
     * @param $request
     * @param $exception
     *
     * Logs the exception
     */
    public function logException($request, $exception)
    {
        if (config('firewall.log_exceptions')) {
            $exception_log = new ExceptionLog();
            if (($exception instanceof QueryException) && ($exception->getCode() != 2002)) {
                $exception_log = $this->prepareRequestData($exception_log, $request);
                $this->prepareExceptionData($exception_log, $exception);
            }
        }
    }

    /**
     * @param $exception_log
     * @param $request
     * @return mixed
     *
     * Prepares request data to be stored
     */
    private function prepareRequestData($exception_log, $request)
    {
        $exception_log->fill([
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
        $exception_log->save();
        return $exception_log;
    }

    /**
     * @param $exception_log
     * @param $exception
     * @return mixed
     *
     * Prepares exception data to be stored
     */
    private function prepareExceptionData($exception_log, $exception)
    {
        $exception_data = [
            // 'original_class' => $exception->getOriginalClassName(),
            'message' => $exception->getMessage(),
            'error_code' => $exception->getCode(),
            'file_name' => $exception->getFile(),
            'line_number' => $exception->getLine(),
            // 'severity' => $exception->getSeverity(),
            // 'trace' => $exception->getTrace(),
            'trace_string' => $exception->getTraceAsString(),
            'previous' => $exception->getPrevious()
        ];
        $exception_log->fill([
            'exception_data' => $exception_data
        ]);
        $exception_log->save();
        return $exception_log;
    }

}