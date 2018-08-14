<?php namespace Someshwer\Firewall\Lib;

/**
 * Class Firewall
 * @package Someshwer\Firewall\Lib
 * @author Someshwer
 * Date: 15-08-2018
 */
class Firewall
{

    /**
     * Firewall constructor.
     */
    public function __construct()
    {

    }

    public function whitelist()
    {
        return array('whitelisted_ips' => config('firewall.whitelist'));
    }

    public function blacklist()
    {
        return array('blacklisted_ips' => config('firewall.blacklist'));
    }

    public function whiteAndBlackList()
    {

    }

    public function acceptList()
    {
        return ['accept_listed_ips' => config('firewall.accept')];
    }

    public function rejectList()
    {
        return ['reject_listed_ips' => config('firewall.reject')];
    }

    public function acceptAndRejectList()
    {

    }

    public function getAllIpAddresses()
    {

    }

    public function firewallLog()
    {
        //TODO:: Also accept dates if dates present
    }

    public function firewallRequestsLog()
    {

    }


}