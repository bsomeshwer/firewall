<?php

Route::get('sayHello',function(){
    dd('Hello World!!');
});


Route::get('testModel',function(FirewallIPAddress $fireIp){
  
    $fireIp->create(['path'=>'to']);

});

