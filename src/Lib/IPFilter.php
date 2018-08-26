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
     * @return string $list
     */
    public function getFilterType()
    {
        $filter_type = $this->none;
        if (config('firewall.enable_whitelist') && config('firewall.enable_blacklist')) {
            $filter_type = $this->black_list;
        }
        if (config('firewall.enable_whitelist') && (!config('firewall.enable_blacklist'))) {
            $filter_type = $this->white_list;
        }
        if (config('firewall.enable_blacklist') && (!config('firewall.enable_whitelist'))) {
            $filter_type = $this->black_list;
        }
        return $filter_type;
    }

    /**
     * Filters whitelist and returns 'true' if current request ip is not in whitelist
     *
     * @param object $request
     * @return bool
     */
    public function filterWhiteList($request)
    {
        if (in_array($request->ip(), config('firewall.whitelist'))) {
            return $this->block;
        }
        return false;
    }

    /**
     * Filters blacklist and returns 'true' if current request ip is available in blacklist
     *
     * @param [type] $request
     * @return bool
     */
    public function filterBlackList($request)
    {
        if (in_array($request->ip(), config('firewall.blacklist'))) {
            return $this->block;
        }
        return false;
    }

}
