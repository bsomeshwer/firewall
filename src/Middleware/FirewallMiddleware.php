<?php namespace Someshwer\Firewall\Middleware;

use Closure;
use Someshwer\Firewall\Lib\IPFilter;
use Someshwer\Firewall\src\Entities\FirewallLog;

/**
 * @author Someshwer<bsomeshwer89@gmail.com>
 * Date: 11-08-2018
 * Time: 20:42 IST
 *
 * This class filters ip address of every incoming request before actual request is handled.
 * Filter in blacklist or whitelist are two configured options available.
 *
 * This also logs every incoming request to the application.
 * The log data is available in 'firewall_requests_log' table.
 *
 * FirewallMiddleware class
 */
class FirewallMiddleware
{
    /**
     * The ip addresses defined in this variable can be ignored even if they white listed.
     *
     * @var array
     */
    protected $ignore;

    /**
     * The ip addresses defined in this variable can be acceptable even if they are blacklisted.
     *
     * @var array
     */
    protected $accept;

    /**
     * IPFilter class object
     *
     * @var object
     */
    private $ip_filter;

    /**
     * Redirect url for black and white list
     *
     * @var string
     */
    private $redirect_url;

    private $log_request;

    public function __construct(IPFilter $ipFilter)
    {
        $this->ip_filter = $ipFilter;
        $this->redirect_url = config('firewall.redirect_url');
        $this->accept = config('firewall.accept');
        $this->ignore = config('firewall.ignore');
        $this->log_request = config('firewall.log_request');
    }

    private function logRequest($request)
    {
        $firewall_log = new FirewallLog;
        $firewall_log->fill([
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
        $firewall_log->save();
        return $firewall_log;
    }

    /**
     * Checking accept list whether it has current request ip or not
     *
     * @param object $request
     * @return bool
     */
    private function checkAcceptList($request)
    {
        $status = false;
        if (count($this->accept) > 0) {
            if (in_array($request->ip(), $this->accept)) {
                $status = true;
            }
        }
        return $status;
    }

    /**
     * Checking ignore list whether it has current request ip or not
     *
     * @param object $request
     * @return bool
     */
    private function checkIgnoreList($request)
    {
        $status = false;
        if (count($this->ignore) > 0) {
            if (in_array($request->ip(), $this->ignore)) {
                $status = true;
            }
        }
        return $status;
    }

    /**
     * Set default values to black_listed and accepted attributes
     *
     * @param $log_request
     */
    private function setDefaultsToBlackListAndAccepted($log_request)
    {
        if ($log_request) {
            $log_request->fill(['accepted' => false]);
            $log_request->fill(['black_listed' => false]);
            $log_request->save();
        }
    }

    /**
     * Set black_listed and accepted attributes and store in a table
     *
     * @param $request
     * @param $log_request
     * @param $ip_filter
     */
    private function saveLogForBlackAndAcceptList($request, $log_request, $ip_filter)
    {
        if ($ip_filter->filterBlackList($request)) {
            if ($log_request) {
                $log_request->fill(['black_listed' => true]);
                $log_request->save();
            }
        }
        if ($this->checkAcceptList($request)) {
            if ($log_request) {
                $log_request->fill(['accepted' => true]);
                $log_request->save();
            }
        }
    }

    /**
     * This methods redirects to a specified url if the ip address is really blocked.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    private function redirectIfBlocked($request)
    {
        // Checking accept list if blacklist is enabled
        if (!$this->checkAcceptList($request)) {
            // Checking request ip address is present in black list or not
            if ($this->ip_filter->filterBlackList($request)) {
                // If present redirect the request custom redirection url
                return redirect($this->redirect_url);
            }
        }
        return null;
    }

    /**
     * Set values to white_listed and rejected attributes
     *
     * @param $request
     * @param $log_request
     */
    private function setValuesToWhiteListedAndIgnored($request, $log_request)
    {
        if ($this->ip_filter->filterWhiteList($request)) {
            if ($log_request) {
                $log_request->fill(['white_listed' => true]);
                $log_request->fill(['ignored' => false]);
                $log_request->save();
            }
        }
    }

    /**
     * Redirecting to specified url if the ip in rejected list
     *
     * @param $request
     * @param $log_request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    private function redirectIfRejectListed($request, $log_request)
    {
        // Checking ignore list if whitelist is enabled
        if ($this->checkIgnoreList($request)) {
            if ($log_request) {
                $log_request->fill(['ignored' => true]);
                $log_request->save();
            }
            // If present redirect the request custom redirection url
            return redirect($this->redirect_url);
        }
        return null;
    }

    private function redirectIfNotWhiteListed($request, $log_request)
    {
        // Checking request ip address is present in whitelist or not
        if (!$this->ip_filter->filterWhiteList($request)) {
            if ($log_request) {
                $log_request->fill(['white_listed' => false]);
                $log_request->save();
            }
            // If not present redirect the request custom redirection url
            return redirect($this->redirect_url);
        }
        return null;
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
        $log_request = null;
        if ($this->log_request) {
            $log_request = $this->logRequest($request);
        }

        // Checking if blacklist enabled or not
        if ($this->ip_filter->getFilterType() == 'BLACKLIST') {
            $this->setDefaultsToBlackListAndAccepted($log_request);
            $this->saveLogForBlackAndAcceptList($request, $log_request, $this->ip_filter);
            $this->redirectIfBlocked($request);
        }

        // Checking if whitelist enabled or not
        if ($this->ip_filter->getFilterType() == 'WHITELIST') {
            $this->setValuesToWhiteListedAndIgnored($request, $log_request);
            $this->redirectIfRejectListed($request, $log_request);
            $this->redirectIfNotWhiteListed($request, $log_request);
        }
        // Proceeding further with the actual request
        return $next($request);
    }

}
