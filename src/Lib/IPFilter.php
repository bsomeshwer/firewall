<?php namespace Someshwer\Firewall\Lib;

/**
 * This class filters ips forwarded from FirewallMiddleware
 *
 * IPFilter class
 */
class IPFilter
{

    /**
     * Determines whether to block current request or not
     *
     * @var boolean
     */
    private $block;

    /**
     * Sets the constant to  'BLACKLIST'
     *
     * @var string
     */
    private $black_list;

    /**
     * Sets the constant to 'WHITELIST'
     *
     * @var string
     */
    private $white_list;

    /**
     * Sets the constant to 'NONE'
     *
     * @var string
     */
    private $none;

    /**
     * Constructor function
     */
    public function __construct()
    {
        $this->block = true;
        $this->black_list = 'BLACKLIST';
        $this->white_list = 'WHITELIST';
        $this->none = 'NONE';
    }

    /**
     * Returns one of the filter type i.e; 'BLACKLIST', 'WHITELIST', or 'NONE'
     *
     * @return $list
     */
    public function getFilterType()
    {
        $list = $this->none;
        if ((config('firewall.whitelist_enabled') == true) && (config('firewall.blacklist_enabled') == true)) {
            $list = $this->black_list;
        }
        if (config('firewall.whitelist_enabled') == true && config('firewall.blacklist_enabled') == false) {
            $list = $this->white_list;
        }
        if (config('firewall.blacklist_enabled') == true && config('firewall.whitelist_enabled') == false) {
            $list = $this->black_list;
        }
        return $list;
    }

    /**
     * Filters whitelist and returns 'true' if current request ip is not in whitelist
     *
     * @param object $request
     * @return void
     */
    public function filterWhiteList($request)
    {
        if (!in_array($request->ip(), config('firewall.whitelist'))) {
            return $this->block;
        }
    }

    /**
     * Filters blacklist and returns 'true' if current request ip is available in blacklist
     *
     * @param [type] $request
     * @return void
     */
    public function filterBlackList($request)
    {
        if (in_array($request->ip(), config('firewall.blacklist'))) {
            return $this->block;
        }
    }

}
