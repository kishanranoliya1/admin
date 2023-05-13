<?php

function translate($key, $lang = null)
{
   
        return $key;
    
}

    function my_asset($path, $secure = null)
    {
       
            return app('url')->asset('public/' . $path, $secure);
            // return app('url')->asset('https://demo.activeitzone.com/ecommerce/public/' . $path, $secure);
        
    }
    


