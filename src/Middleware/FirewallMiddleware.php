<?php namespace Someshwer\Firewall\Middleware;

use Closure;
use Someshwer\Firewall\Lib\IPFilter;

/**
 * FirewallMiddleware class
 *
 * This class filters ip address of every incoming request before actual request is handled.
 * Filter in blacklist or whitelist are two configured options available.
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

    public function __construct(IPFilter $ipFilter)
    {
        $this->ip_filter = $ipFilter;
        $this->redirect_url = config('firewall.redirect_url');
        $this->accept = config('firewall.accept');
        $this->ignore = config('firewall.ignore');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Someshwer\Firewall\Entities\FirewallIPAddress::create(['path'=>'hi']);
        // Checking if blacklist enabled or not
        if ($this->ip_filter->getFilterType() == 'BLACKLIST') {
            // Checking accept list if blacklist is enabled
            if (!$this->checkAcceptList($request)) {
                // Checking request ip address is present in black list or not
                if ($this->ip_filter->filterBlackList($request)) {
                    // If present redirect the request custom redirection url
                    return redirect($this->redirect_url);
                }
            }
        }
        // Checking if whitelist enabled or not
        if ($this->ip_filter->getFilterType() == 'WHITELIST') {
            // Checking ignore list if whitelist is enabled
            if ($this->checkIgnoreList($request)) {
                // If present redirect the request custom redirection url
                return redirect($this->redirect_url);
            }
            // Checking request ip address is present in whitelist or not
            if ($this->ip_filter->filterWhiteList($request)) {
                // If not present redirect the request custom redirection url
                return redirect($this->redirect_url);
            }
        }
        // Proceeding further with the actual request
        return $next($request);
    }

    /**
     * Checking accept list whether it has current request ip or not
     *
     * @param object $request
     * @return void
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
     * @return void
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

}
