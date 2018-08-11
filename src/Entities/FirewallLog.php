<?php namespace Someshwer\Firewall\src\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Firewall model
 */
class FirewallLog extends Model
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
    protected $fillable = ['path', 'method', 'uri', 'url', 'full_url', 'query', 'file_name', 'http_host',
        'http_user_agent', 'ip_address', 'black_listed', 'white_listed', 'accepted', 'ignored', 'all_request_data'];

    /**
     * Type casting
     *
     * @var array
     */
    protected $casts = [
        'black_listed' => 'boolean',
        'white_listed' => 'boolean',
        'accepted' => 'boolean',
        'ignored' => 'boolean',
        'query' => 'json',
        'all_request_data' => 'json'
    ];

}