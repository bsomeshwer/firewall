<?php namespace Someshwer\Firewall\src\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Firewall
 * @package Someshwer\Firewall\src\Facades
 * @author Someshwer Bandapally
 * Date: 15-08-2018
 */
class Firewall extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'firewall';
    }

}