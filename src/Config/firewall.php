<?php
/**
 * Configuration file of firewall package
 *
 * @author Someshwer<bsomeshwer89@gmail.com>
 * Date: 13-10-2018
 */
return [

    /**
     * It enables or disables blacklist.
     * Set it to true if you want to enable blacklist otherwise, set it false.
     */
    'enable_blacklist' => true,

    /**
     * It enables or disables whitelist.
     * Set it to true if you want to enable whitelist otherwise, set it false.
     */
    'enable_whitelist' => false,

    /**
     * Note:: If both options enabled means 'enable_blacklist', and
     * 'enable_whitelist' both set true, in that case only blacklist will be considered.
     */

    /**
     * Add ip addresses those you want to black.
     */
    'blacklist' => [
        '121.2.5.88', '121.2.5.89', '121.2.5.90',
    ],

    /**
     * Add ip addresses those you want to whitelist.
     */
    'whitelist' => [
        '121.2.5.92', '121.2.5.93', '121.2.5.94',
    ],

    /**
     * Add ip addresses those you want to accept even if they are in blacklist.
     */
    'accept' => [
        '192.0.0.1', '192.0.0.2',
    ],

    /**
     * Add ip addresses those you want reject even if they are in whitelist.
     */
    'reject' => [
        '192.0.0.3', '192.0.0.4', '192.0.0.5',
    ],

    /**
     * Redirects you to this url if request ip is blacklisted or not whitelisted.
     *
     * Note:: Here you can also specify your own custom url
     */
    'redirect_url' => '/',

    /**
     * This enables request logging mechanism.
     * If set tu TRUE every incoming request will be captured and stored in request_log table.
     */
    'log_request' => true

];
