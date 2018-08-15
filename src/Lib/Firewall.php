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

    public function getAllIpAddresses()
    {
        //TODO:: todo
    }

    public function getAllIpAddressesOld()
    {
        $common_list = array_intersect($this->whitelist, $this->blacklist, $this->accept_list, $this->reject_list);
        $white_list = array_diff($this->whitelist, $common_list);
        $black_list = array_diff($this->blacklist, $common_list);
        $accept_list = array_diff($this->accept_list, $common_list);
        $reject_list = array_diff($this->reject_list, $common_list);
        $list_groups = [
            'white' => $white_list,
            'black' => $black_list,
            'accept' => $accept_list,
            'reject' => $reject_list,
            'common' => $common_list
        ];
        $result_list = [];
        foreach ($list_groups as $list_type => $list_group) {
            $result_list[] = $this->repo->getList($list_group, 4, $list_type);
        }
        return array_collapse($result_list);
    }

    public function firewallLog()
    {
        //TODO:: Also accept dates if dates are present
    }

    public function firewallRequestsLog()
    {
        //TODO:: Also accept dates if dates are present
    }


}