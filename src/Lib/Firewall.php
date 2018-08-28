<?php namespace Someshwer\Firewall\Lib;

use Someshwer\Firewall\src\Repo\FirewallRepository;

/**
 * Class Firewall
 * @package Someshwer\Firewall\Lib
 * @author Someshwer
 * Date: 15-08-2018
 */
class Firewall
{

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $whitelist;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $blacklist;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $accept_list;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $reject_list;

    /**
     * @var FirewallRepository
     */
    private $repo;

    /**
     * Firewall constructor.
     * @param FirewallRepository $firewallRepository
     */
    public function __construct(FirewallRepository $firewallRepository)
    {
        $this->repo = $firewallRepository;
        $this->whitelist = config('firewall.whitelist');
        $this->blacklist = config('firewall.blacklist');
        $this->accept_list = config('firewall.accept');
        $this->reject_list = config('firewall.reject');
    }

    /**
     * This package provides some useful information about the package.
     * @return array
     */
    public function info()
    {
        return [
            'package_name' => 'Laravel - Firewall',
            'description' => 'Laravel Firewall package detects unknown ip addresses based on 
            blacklist and whitelist ip addresses. Whitelist and Blacklist are two configuration options 
            any one of them you can set to TRUE based on your requirement. For example if you set blacklist
            to TRUE and have added some ip addresses to blacklist then in that case any request to the 
            application will be blocked by the firewall from those ip addresses that listed in blacklist.
            If you have added them to whitelist only the request from the whitelisted ips can be accepted 
            and remaining all requests will be blocked by the firewall. If you set both black and whitelist 
            to TRUE then in that case the preference will be given to blacklist',
            'latest_release' => '1.2.4',
            'stable_version' => '1.2.4',
            'author' => 'Someshwer Bandapally<bsomeshwer89@gmail.com>'
        ];
    }

    /**
     * Returns all whitelisted ip addresses
     * @return array
     */
    public function whitelist()
    {
        return array('whitelisted_ips' => $this->whitelist);
    }

    /**
     * Returns all black listed ip addresses
     * @return array
     */
    public function blacklist()
    {
        return array('blacklisted_ips' => $this->blacklist);
    }

    /**
     * Returns both combination of white and black listed ip addresses
     * @return array
     */
    public function whiteAndBlackList()
    {
        $common_list = array_intersect($this->whitelist, $this->blacklist);
        $whitelist = array_diff($this->whitelist, $common_list);
        $blacklist = array_diff($this->blacklist, $common_list);
        $list_groups = [
            'white' => $whitelist,
            'black' => $blacklist,
            'common' => $common_list
        ];
        $result_list = [];
        foreach ($list_groups as $list_type => $list_group) {
            $result_list[] = $this->repo->getList($list_group, 2, $list_type);
        }
        return array_collapse($result_list);
    }

    /**
     * Returns accept listed ip addresses
     * @return array
     */
    public function acceptList()
    {
        return ['accept_listed_ips' => $this->accept_list];
    }

    /**
     * Returns reject listed ip addresses
     * @return array
     */
    public function rejectList()
    {
        return ['reject_listed_ips' => $this->reject_list];
    }

    /**
     * Returns both combination of accept and reject listed ip addresses
     * @return array
     */
    public function acceptAndRejectList()
    {
        $common_list = array_intersect($this->accept_list, $this->reject_list);
        $accept_list = array_diff($this->accept_list, $common_list);
        $reject_list = array_diff($this->reject_list, $common_list);
        $list_groups = [
            'accept' => $accept_list,
            'reject' => $reject_list,
            'common' => $common_list
        ];
        $result_list = [];
        foreach ($list_groups as $list_type => $list_group) {
            $result_list[] = $this->repo->getList($list_group, 2, $list_type);
        }
        return array_collapse($result_list);
    }

    /**
     * Returns all ip addresses with their statuses.
     *
     * @return array
     */
    public function getAllIpAddresses()
    {
        $ip_addresses = $this->repo->getUniqueIpAddresses();
        $ip_list_with_statuses = [];
        $index = 0;
        foreach ($ip_addresses as $ip_address) {
            $ip_list_with_statuses[] = $this->repo->getIpStatus($ip_address);
            $index++;
        }
        return $ip_list_with_statuses;
    }

    /**
     * Returns firewall log data along with pagination if pagination is enabled in configuration.
     *
     * @param null $from_date
     * @param null $to_date
     * @return mixed|\Someshwer\Firewall\src\Entities\FirewallLog|\Someshwer\Firewall\src\Entities\FirewallRequestsLogModel
     */
    public function log($from_date = null, $to_date = null)
    {
        $log = $this->repo->getLogInstance();
        if ((($from_date != null) || ($to_date != null))) {
            if (!$this->repo->validateDates($from_date, $to_date)) {
                $log = $this->repo->addWhereBetweenClause($log, $from_date, $to_date);
            }
        }
        $log = $this->repo->addPagination($log);
        return $log;
    }

    /**
     * Returns firewall requests log data along with pagination if pagination is enabled in configuration.
     *
     * @param null $from_date
     * @param null $to_date
     * @return mixed|\Someshwer\Firewall\src\Entities\FirewallLog|\Someshwer\Firewall\src\Entities\FirewallRequestsLogModel
     */
    public function requestLog($from_date = null, $to_date = null)
    {
        $log = $this->repo->getLogInstance('request_log');
        if ((($from_date != null) || ($to_date != null))) {
            if (!$this->repo->validateDates($from_date, $to_date)) {
                $log = $this->repo->addWhereBetweenClause($log, $from_date, $to_date);
            }
        }
        $log = $this->repo->addPagination($log, 'request_log');
        return $log;
    }


}