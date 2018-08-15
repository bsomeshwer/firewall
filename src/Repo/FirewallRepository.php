<?php namespace Someshwer\Firewall\src\Repo;

/**
 * @author Someshwer
 * Date: 15-08-2018
 * @package Someshwer\Firewall\src\Repo
 * Class FirewallRepository
 */
class FirewallRepository
{

    /**
     * Prepares list with white, black, accept, and reject ips
     *
     * @param $item
     * @param $list_type
     * @return array
     */
    private function prepareList($item, $list_type)
    {
        $record = [
            'ip_address' => $item,
            'in_whitelist' => false,
            'in_blacklist' => false,
            'in_accept_list' => false,
            'in_reject_list' => false
        ];
        if ($list_type == 'white') {
            $record['in_whitelist'] = true;
        }
        if ($list_type == 'black') {
            $record['in_blacklist'] = true;
        }
        if ($list_type == 'accept') {
            $record['in_accept_list'] = true;
        }
        if ($list_type == 'reject') {
            $record['in_reject_list'] = true;
        }
        if ($list_type == 'common') {
            $record['in_whitelist'] = true;
            $record['in_blacklist'] = true;
            $record['in_accept_list'] = true;
            $record['in_reject_list'] = true;
        }
        return $record;
    }

    /**
     * Fetches list with white, black, accept, and reject ips
     *
     * @param $list
     * @param $flag
     * @param $list_type
     * @return array
     */
    public function getList($list, $flag, $list_type)
    {
        $result_list = [];
        foreach ($list as $item) {
            if ($flag == 2) {
                $set = $this->prepareList($item, $list_type);
                if (($list_type == 'white') || $list_type == 'black') {
                    $result_list[] = array_except($set, ['in_accept_list', 'in_reject_list']);
                } else {
                    $result_list[] = array_except($set, ['in_whitelist', 'in_blacklist']);
                }
            }
            if ($flag == 4) {
                $result_list[] = $this->prepareList($item, $list_type);
            }
        }
        return $result_list;
    }

}