<?php namespace Someshwer\Firewall\src\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FirewallLog
 * @package Someshwer\Firewall\src\Entities
 * @author Someshwer
 * Date:10-08-2018
 *
 * FirewallLog model
 */
class FirewallLog extends Model
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'firewall_log';

    /**
     * Table columns
     *
     * @var array
     */
    protected $fillable = ['path', 'method', 'uri', 'url', 'full_url', 'query', 'file_name',
        'http_host', 'http_user_agent', 'ip_address', 'black_listed', 'white_listed', 'accepted',
        'rejected', 'all_request_data', 'response_data'];

    /**
     * Type casting
     *
     * @var array
     */
    protected $casts = [
        'black_listed' => 'boolean',
        'white_listed' => 'boolean',
        'accepted' => 'boolean',
        'rejected' => 'boolean',
        'query' => 'json',
        'all_request_data' => 'json',
        'response_data' => 'json'
    ];

}