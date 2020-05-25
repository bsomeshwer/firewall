<?php

/**
 * @author Someshwer Bandapally
 * Date: 19-08-2018
 *
 * This route provides some useful information about the package.
 */
Route::get('firewall/info', function () {
    return [
        'package_name' => 'Laravel - Firewall',
        'description'  => 'Laravel Firewall package detects unknown ip addresses based on 
            blacklist and whitelist ip addresses. Whitelist and Blacklist are two configuration options 
            any one of them you can set to TRUE based on your requirement. For example if you set blacklist
            to TRUE and have added some ip addresses to blacklist then in that case any request to the 
            application will be blocked by the firewall from those ip addresses that listed in blacklist.
            If you have added them to whitelist only the request from the whitelisted ips can be accepted 
            and remaining all requests will be blocked by the firewall. If you set both black and whitelist 
            to TRUE then in that case the preference will be given to blacklist',
        'latest_release' => '2.2.1',
        'stable_version' => '2.2.1',
        'author'         => 'Someshwer Bandapally<bsomeshwer89@gmail.com>',
    ];
});

Route::get('firewall/unauthorized/redirect', function () {
    return view('package_redirect::redirect_view');
});
