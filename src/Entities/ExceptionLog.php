<?php

namespace Someshwer\Firewall\src\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExceptionLog.
 *
 * @author Someshwer
 * Date:15-09-2018
 */
class ExceptionLog extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'exception_log';

    /**
     * Table columns.
     *
     * @var array
     */
    protected $fillable = ['path', 'method', 'uri', 'url', 'full_url', 'query',
        'file_name', 'http_host', 'http_user_agent', 'ip_address', 'all_request_data',
        'exception_data', ];

    /**
     * Type casting.
     *
     * @var array
     */
    protected $casts = [
        'query'            => 'json',
        'all_request_data' => 'json',
        'exception_data'   => 'json',
    ];
}
