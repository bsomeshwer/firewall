<?php namespace Someshwer\Firewall\src\Repo;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Someshwer\Firewall\src\Entities\FirewallLog;
use Someshwer\Firewall\src\Entities\FirewallRequestsLogModel;

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

    /**
     * Get log instance based on type
     *
     * @param null $log_type
     * @return FirewallLog|FirewallRequestsLogModel
     */
    public function getLogInstance($log_type = null)
    {
        if ($log_type == 'request_log') {
            return $request_log = new FirewallRequestsLogModel();
        }
        return $log = new FirewallLog;
    }

    /**
     * Validating dates and dates must be in Y-m-d format
     *
     * @param $from_date
     * @param $to_date
     * @return bool
     */
    public function validateDates($from_date, $to_date)
    {
        $validation = Validator::make([
            'from_date' => $from_date,
            'to_date' => $to_date
        ], [
            'from_date' => 'date_format:Y-m-d',
            'to_date' => 'date_format:Y-m-d|after:from_date'
        ]);
        return $validation->fails();
    }

    /**
     * Add where clause to a query if dates are present
     * in specified format i.e.; Y-m-d
     *
     * @param $log
     * @param $from_date
     * @param $to_date
     * @return mixed
     */
    public function addWhereBetweenClause($log, $from_date, $to_date)
    {
        $from = Carbon::createFromFormat('Y-m-d', $from_date)->format('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d', $to_date)->format('Y-m-d');
        return $log->whereBetween(DB::raw('DATE(`created_at`)'), [$from, $to]);
    }

    /**
     * Adding pagination if config option for pagination is enabled.
     *
     * @param $log
     * @param null $log_type
     * @return mixed
     */
    public function addPagination($log, $log_type = null)
    {
        if ($log_type == 'request_log') {
            $is_pagination = config('firewall.firewall_requests_log_pagination.enabled');
            $records_per_page = config('firewall.firewall_requests_log_pagination.per_page');
        } else {
            $is_pagination = config('firewall.firewall_log_pagination.enabled');
            $records_per_page = config('firewall.firewall_log_pagination.per_page');
        }
        return $is_pagination ? $log->paginate($records_per_page) : $log->get();
    }

}