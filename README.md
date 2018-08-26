# Firewall

Firewall enables us to whitelist and blacklist IP addresses for LARAVEL applications. 
This package detects an unknown ip addresses using filtering mechanism based on whitelist and blacklist arrays and configuration options.

#Installation
Open terminal, go to root directory and run the following command:

    composer require someshwer/firewall  
    
Now, the package will be installed.

Add 'Someshwer\MyWorld\WorldDataServiceProvider::class' class to 'providers' array in config/app.php file.

    Ex:- 'providers' => [
                 ...,
                 Someshwer\Firewall\FirewallServiceProvider::class,
             ]
    
Now add this alias to 'aliases' array in config/app.php`

    Ex:- 'aliases' => [
          ...,
         'Firewall' => Someshwer\Firewall\Facades\Firewall::class,
         ]

Now publish the configuration and migrations.

      $ php artisan vendor:publish --provider="Someshwer\Firewall\FirewallServiceProvider"

This package has two migration files to create two tables 
1. firewall_log
2. firewall_requests_log


        Next, run 'php artisan migrate' command to create tables in your database.
        

Next, add middleware i.e.; 'Someshwer\Firewall\Middleware\FirewallRequestsLog::class' to 
$middlewareGroups array in 'app\Http\Kernel.php'.

        EX:- protected $middlewareGroups = [
                    'web' => [
                    ...,
                    Someshwer\Firewall\Middleware\FirewallRequestsLog::class
                    ],
                    'api' => [
                    ...
                    ],
        ];
    
    
This logs and stores every incoming request in 'firewall_request_log' table with all 
request and response data.

Next, add another middleware i.e.; 'Someshwer\Firewall\Middleware\FirewallMiddleware::class' to 
      $routeMiddleware array in 'app\Http\Kernel.php'.
      
      Ex:- protected $routeMiddleware = [
                   ...,
                   'firewall' => \Someshwer\Firewall\Middleware\FirewallMiddleware::class
               ];

This is a route specific middleware and it must be used with individual routes or route groups. This middleware works for those routes which assigned with this middleware. 

    Note: The route which is configured as redirect route must not be assigned with this middleware.
    This middleware should not be used with redirect route. Here, redirect route is one where 
    the request will be redirected to that route when the request is blocked.

The routes or requests which has this middleware can be logged and stored in 'firewall_log' table
along with request data, response data, and whitelist, blacklist, accept, and ignore data.

        Important Notice:- This middleware must be used as route specific middleware and must be added
        to '$routeMiddleware' property in 'app\Http\Kernel.php'. The package will not work if you add this
        middleware in any other property in 'app\Http\Kernel.php'. Let us say if you add this to web 
        middleware group i.e.; $middlewareGroups in 'app\Http\Kernel.php' then package will not be 
        worked and leads to infinite loop.

        #Using route specific middleware i.e; 'firewall' middleware for route:

            Route::group(['middleware' => 'firewall'], function () {

                Route::get('/', function () {
                    // Uses firewall middleware
                });

                Route::get('/test', function () {
                    // Uses firewall middleware
                });

            });             
                            (OR)

            Route::get('/test', function () {
                  // Uses firewall middleware
            })->middleware('firewall');

            Or you can also use this in controller constructor based on your interest.

That's it !! You are done with package installation...

#Usage


#Blacklist
In order to block requests from any ip address:

First blacklist configuration option must be enabled by setting 'enable_blacklist' option to TRUE in the 'firewall' config file. And then add the ip addresses those need to be blocked to 'blacklist' array in 'firewall' config file. If multiple ip addresses are the to be blocked then add all those ip addresses to 'blacklist' array, and they must be seperated with comma.
    
    Note: If blacklist is enabled then all the requests from specified ip addresses in 
    blacklist array will be blocked. And the requests from other ip adresses which you 
    have not added to blacklist will be executed successfully.
    
#Whitelist
In order to allow the requests only from specific ip addresses, use whitelist:

For this, whitelist configuration option must be enabled by setting 'enable_whitelist' to TRUE in the 'firewall' config file. And then add the ip addresses those must be allowed to 'whitelist' array in 'firewall' configuration file. If multiple ip addresses are there to be allowed then list them in 'whitelist' array and they must be seperated with comma.

    Note: If whitelist is enabled then all the requests from specified ip addresses in 
    whitelist array will be allowed. And the requests from other ip adresses which you 
    have not added to whitelist will be blocked.
     
        Important Information: You can not enable both whitelist and blacklist at the same time. In case you set 
        both of them to TRUE then the preference will be given to blacklist and the whitelist 
        will not be considered. 

#Accept & Reject

In order to allow or block requests from any ip addresses irrespective of whitelist and blacklist then in that case, must use accept and ignore configuration options.

Let us assume, some ip addresses added to whitelist and still you want to block them then add them to 'reject' array in config file. The ip addresses those added to 'reject' array must be blocked even if they are in 'whitelist' array and whitelist configuration option is enabled.

Assume that, you have added ip addresses to blacklist and still you want to allow them then in that case they must be added to 'accept' array in 'firewall' config file. The ip addresses those added to 'accept' array in 'firewall' config file must be allowed or executed even if they are in 'blacklist' array and blacklist configuration option is enabled.

#Redirect url:
Every blocked request must be redirected to specific web page which has configured in 'firewall' configuration file. By default it will be redirected to firewall's block page. If you want you can change the url by setting your own url to 'redirect_url' config option in 'firewall' config page.

    Note: The route which is configured as redirect route must not be assigned with 'firewall' middleware.
    The 'firewall' middleware should not be used with redirect route. Here, redirect route is one where 
    the request will be redirected to that url when the request is blocked.


#Commands:

-> To display all ip addresses that are added to whitelist array
      
    php artisan firewall:whitelist
    
This command displays all whitelisted ip addresses on console.

-> To display all ip addresses that are added to blacklist array

    php artisan firewall:blacklist
    
This command displays all blacklisted ip addresses on console.

-> To display all ip addresses that are added to accept and reject array are

    php artisan ip:list --accept
    php artisan ip:list --reject

This commands will display all accept and reject listed ip addresses on console.

If no option is given then 'NoOptionException' will be thrown.

#Ip Helpers

    Firewall::whitelist();
 
It returns all ip addresses in whitelist array in 'firewall' config file.

    Firewall::blacklist();
    
It returns all ip addresses in blacklist array.

    Firewall::whiteAndBlackList();
    
It returns all ip addresses in both blacklist and whitelist arrays.

    Firewall::acceptList();
    
It returns all ip addresses in 'accept' array in 'firewall' config file.

    Firewall::rejectList();
    
It returns all ip addresses in 'reject' array.

    Firewall::acceptAndRejectList();
    
It returns all ip addresses in both 'accept' and 'reject' array.

    Firewall::getAllIpAddresses();
    
It returns all ip addresses in all 'whitelist', 'blacklist', 'accept' and 'reject' array in 
'firewall' config file. It returns unique ip addresses along with status flag that determines 
whether ip address is whitelisted, blacklisted, accepted or rejected.

        Note: All these arrays whitelist, blacklist, accept and reject arrays are available
        in 'config/firewall.php' config file.

#Log Helpers

    #Firewall::log($from_date, $to_date);
    
This method returns all log records from firewall_log table. The results can also be paginated. The 'firewall_log_pagination.enabled' must be set to TRUE for paginating results. In order to set the number of records per page then must use 'firewall_log_pagination.per_page' config option.

This function also accepts date parameters. If 'from date' and 'to date' is given then it returns all the records between specified dates.

The date format must be 'Y-m-d'. For example: If the from date is 12-Jan-2018 then form_date must be provided as '2018-01-12'.

    * If wrong date format is given then all the records will be returned irrespective of dates.


    #Firewall::requestLog();

This method returns all requests log records from firewall_request_log table. These results can also be paginated. The 'firewall_request_log_pagination.enabled' option must be set to TRUE for paginating results. In order to set the number of records per page then must use 'firewall_request_log_pagination.per_page' config option.

This function also accepts date parameters. If 'from_date' and 'to_date' is given then it returns all the records between specified dates.

The date format must be 'Y-m-d'. For example: If the to date is 12-Jan-2018 then to_date must be provided in the following format i.e.; '2018-01-12'.

    * If wrong date format is given then all the records will be returned irrespective of dates.

#Difference b/w firewall log and firewall request log

Firewall log depends on the firewall system. It tracks only those requests that assigned with 'firewall' middleware. And it stores the request and response information along with blacklist and whitelist statuses information.

Firewall request log tracks every incoming request and stores only request and response information into the database table called 'firewall_requests_log'.

