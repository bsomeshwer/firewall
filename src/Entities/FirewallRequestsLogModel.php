<?php namespace Someshwer\Firewall\src\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FirewallRequestsLogModel
 *
 * @property mixed response
 * @package Someshwer\Firewall\src\Entities
 * @author Someshwer Bandapally
 * Date: 14-08-2018
 */
class FirewallRequestsLogModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'firewall_requests_log';

    /**
     * Table columns
     *
     * @var array
     */
    protected $fillable = ['path', 'method', 'uri', 'url', 'full_url', 'query', 'file_name',
        'http_host', 'http_user_agent', 'ip_address', 'all_request_data', 'response_data'];

    /**
     * Type casting
     *
     * @var array
     */
    protected $casts = [
        'query' => 'json',
        'all_request_data' => 'json'
    ];

    public function setResponseDataAttribute($value)
    {
        $this->attributes['response_data'] = ($value == null) ? null : serialize($value);
    }

    public function getResponseDataAttribute($value)
    {
        return ($value == null) ? null : unserialize($value);
    }

}