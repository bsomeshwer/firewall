<?php 

use Illuminate\Database\Eloquent\Model;

/**
 * Firewall model
 */
class FirewallIPAddress extends Model
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
    protected $fillable = ['path','method','uri','url','query','file_name','http_host','http_user_agent','ip_address'];
 
}
