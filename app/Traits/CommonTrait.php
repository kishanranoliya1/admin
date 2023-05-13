<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HoroscopeClient;
use App\Models\ClientAuthApi;
use App\Lib\Swedivine;
use DateTime;
use Carbon\Carbon;

trait CommonTrait {

    public function __construct() {
	
		$this->swetest = new Swedivine\Swedivine();
        $this->swetest->setMaskPath(true);	
	}
    /**
     * Comman method to validate api request, validate user, domain, api and api's extra parameters
     */
    public function validate_api_request(Request $request, $rules, $auth_token_user_id) {
    
        
        $resp = array("success" => 0, "msg" => "Something went wrong! Please try again.");
        $api_key = ($request->has('api_key')) ? stripslashes(trim($request->get('api_key'))) : '';
        $api_id = ($request->has('api_id')) ? intval($request->get('api_id')) : 0;
        $errcnt = 0;
        $user_details = array();
        // $user_details = HoroscopeClient::select('*')
        //                     ->where('api_key', $api_key)
        //                     ->first();
        if (strlen($api_key) > 0) {

            //verify api key
            $api_key = $this->check_api_key($api_key);
            
            $api_key = ($api_key === 0) ? '' : $api_key;

        }

        if ($api_key == "") {
            $resp = array("success" => 2, "msg" => "API key missing");
            $errcnt++;
        }
        if ($errcnt <= 0) {
            $user_details = HoroscopeClient::get_user_by_api_key($api_key);
            if (!isset($user_details->id) || $user_details->id == null || $user_details->id <= 0) {
                $resp = array("success" => 2, "msg" => "Invalid API key");
                $errcnt++;
            }
        }

        if ($errcnt <= 0) {
            
            //check autorized token user and api key user is same or not
            if($auth_token_user_id != $user_details->id) {
                $resp = array('success' => 2, 'msg' => "Authorization token passed for API Key is not of the same user");
                $errcnt++;
            }

        }

        if ($errcnt <= 0) {
           
            $access = self::validate_domain($user_details, $request);
            
            if($access == 1) {
                
                $auth_api = self::api_authentication($user_details, $api_id);

                if ($auth_api == 0) {
                    $resp = array("success" => 2, "msg" => "You are not authorized to access this API");
                    $errcnt++;
                } else if ($auth_api == 2) {
                    $resp = array("success" => 2, "msg" => "You can use this API key only for registerd website on divine");
                    $errcnt++;
                } else if ($auth_api == -1) {
                    $resp = array("success" => 2, "msg" => "Your subscription has expired");
                    $errcnt++;
                } else {
                    //is valid request   
                    
                    //validate api's extra parameters
                    $resp = $this->validate_extra_params($rules, $request);

                }

            } else {

                $resp = array("success" => 2, "msg" => "You are not authorized to access this API");
                $errcnt++;

            }

        }

        if ($errcnt == 0 && $resp['success'] == 1) {
            $resp = array("success" => 1, "msg" => "Is valid API request");
        }

        return $resp;

    }

    /**
     * Method is used to check value of passed api_key is valid or not
     */
    public function check_api_key($key) {

        $explodKey  = explode('-', $key);

        if (count($explodKey) == 1) {

            return $explodKey[0];

        } else {

            if ( base64_encode(base64_decode($explodKey[1], true)) === $explodKey[1]){

                if (base64_encode((strtotime(date('Y-m-d')))) == $explodKey[0]) {

                    $orignalKey = base64_decode($explodKey[1]);
                    return $orignalKey;

                } else {

                    return 0;

                }

            } else {
                return $explodKey[0];
            }

        }

    }

    /**
     * Method is used to validate user's source url
     */
    public static function validate_domain($user_details, Request $request) {

        $days = round((strtotime($user_details['trial_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24));

        if ($days >= 0) {

            $access = 1;

        } else {
           
            // if (isset($_SERVER['HTTP_REFERER'])) {
            if (isset($_SERVER['REMOTE_HOST'])) {

                $parse = parse_url($_SERVER['REMOTE_HOST']);
                $domain = $parse['host'];
                if ($domain == 'localhost') {
                    $access = 1;
                } else {

                    // $domain_query = mysqli_query($con,"SELECT * FROM horoscope_clients WHERE source_url LIKE '%".$domain."%' AND id=".$client_id);
                    $remote_user = HoroscopeClient::get_user_by_source_url($domain, $user_details['id']);

                    if (isset($remote_user->id) && $remote_user->id != null && $remote_user->id == $user_details['id']) {
                        $access = 1;
                    }
                    /*
                        * As per discussion on 02-11-2022 bypass_ip should not be checked 
                        * https://app.asana.com/0/1201275519613200/1203264452643811/f
                        */
        //                        else if($bypass_ip == YES)
                    else if ($user_details['bypass_ip'] == YES || $user_details['bypass_ip'] == NO) {
                        $access = 1;
                    }
                }

            } else {

                $ip = trim($_SERVER['REMOTE_ADDR']);
                // $ip_query = mysqli_query($con,"SELECT * FROM horoscope_clients WHERE source_ip = '".$ip."' AND id=".$client_id);

                $remote_user = HoroscopeClient::get_user_by_source_ip($ip, $user_details['id']);

                // if (mysqli_num_rows($ip_query) > 0) {
                if (isset($remote_user->id) && $remote_user->id != null && $remote_user->id == $user_details['id']) {
                    $access = 1;
                } else {
                    /*
                        * As per discussion on 02-11-2022 bypass_ip should not be checked 
                        * https://app.asana.com/0/1201275519613200/1203264452643811/f
                        */
//                        if($bypass_ip == YES)
//                        {
//                            $access = 1;
//                        }
                    $access = 1;
                }

            }

            return $access;

        }

    }

    /**
     * Method is used to do api authentication
     * We check here subscription user is active or expired
     * If subscription is expired we check is subscription is in grace period or not
     * Return codes
     * -1: Your subscription has expired
     * 0: You are not authorized to access this API
     * 1: You can access this API
     * 2: You can use this API key only for registerd website on divine
     */
    public static function api_authentication($user_details, $api_id) {

        $days = round((strtotime($user_details['trial_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24));

        if ($days >= 0) {

            $response = 1;

        } else {

            $galaxyIdArr = array(1, 2, 3, 4, 5);

            if (in_array($api_id, $galaxyIdArr)) {

                //get client_auth_api details
                $auth_api_details = ClientAuthApi::select('*')
                                                    ->where('client_id', $user_details['id'])
                                                    ->where(function ($query) use($api_id) {
                                                        $query->where('api_id', $api_id)
                                                                ->orWhere('api_id', 6)
                                                                ->orWhere('api_id', 28);
                                                    })
                                                    ->first();
                
            } else {
              
                //get client_auth_api details
                $auth_api_details = ClientAuthApi::select('*')
                                                    ->where('client_id', $user_details['id'])
                                                    ->where(function ($query) use($api_id) {
                                                        $query->where('api_id', $api_id)
                                                                ->orWhere('api_id', 28);
                                                    })
                                                    ->first();
                                                    
            
            }

            if (!isset($auth_api_details->id)) {

                //no record found in client_auth_api table
                $response = 0;

            } else {

                $days = round((strtotime($auth_api_details['subscription_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24));
               
                if ($days < 0) {

                    //subscription has expired. We will check here grace period is expired or not

                    //==================================
                    //Swagat code start
                    
                    $is_grace_period = $auth_api_details['is_grace_period'];
                    $grace_period_end_date = date('d-m-Y', strtotime($auth_api_details['subscription_end_date']. ' + '.env('GRACE_PERIOD').' days'));

                    if ($is_grace_period == 1 && strtotime($grace_period_end_date) > strtotime(date('Y-m-d'))) {
                        //valid till grace period
                        if (self::authorized_domain($user_details['id']) == 1) {
                            $response = 1;
                        } else {
                            $response = 2;
                        }
                    } else {
                        //expired
                        $response = -1;
                    }
                    
                    //Swagat code end
                    //==================================
                    // $response = -1;
                
                } else {

                    //subscription is active

                    if (self::authorized_domain($user_details['id']) == 1) {
                        $response = 1;
                    } else {
                        $response = 2;
                    }
                    
                }
            }

        }

        return $response;

    }

    /**
     * Method is used to check domain is authorized domain or not
     */
    public static function authorized_domain($client_id) {

        $setDate = "2022-11-03";

        $whitelistip = array(
            '127.0.0.1',
            '::1'
        );

        $whitelist = array(
            'dev',
            'localhost',
            'staging',
            'local',
            'stage',
            'test',
        );
            
        // if ($_SERVER['HTTP_REFERER'] != '') {
        if (isset($_SERVER['REMOTE_HOST']) && $_SERVER['REMOTE_HOST'] != '') {
            
            if ( $parts = parse_url($_SERVER['REMOTE_HOST']) ) {

                $host = $parts[ "host" ];
                if ($host !='') {
                    if (in_array($host, $whitelist)) { 
                        return 1;
                    } else if (in_array($host, $whitelistip)) {
                        return 1;
                    }
                    
                }
                
            }

            $domain = parse_url($_SERVER['REMOTE_HOST'], PHP_URL_HOST);
        
            if ($client_id !='' && $domain !='')  {
   
                $hostArr = explode('.', $domain);
                
                if (in_array($hostArr[0], $whitelist) ) {
                
                    //check subdomain of user
                    $sub = $hostArr[0].".";
                    
                    $subdomain = str_replace($sub, "", $domain);

                    $client_details = HoroscopeClient::select('*')
                                                        ->where('id', $client_id)
                                                        ->where(function($query) {
                                                            $query->where('authorized_website', 'LIKE', '%'.$subdomain.'%')
                                                                    ->orWhereDate('registered_on', $setDate);
                                                        })
                                                        ->first();
    
                    if (isset($client_details->id) && $client_details->id != null && $client_details->id = $client_id) {
                        return 1;
                    } else {
                        return 0;
                    }

                } else {
                    
                    //check domain of user
                    $client_details = HoroscopeClient::select('*')
                                                        ->where('id', $client_id)
                                                        ->where(function($query) {
                                                            $query->where('authorized_website', 'LIKE', '%'.$domain.'%')
                                                                    ->orWhereDate('registered_on', '<', $setDate);
                                                        })
                                                        ->first();

                    if (isset($client_details->id) && $client_details->id != null && $client_details->id = $client_id) {
                        return 1;
                    } else {
                        return 0;
                    }
                    
                }
                
            } else {
                return 0;
            }

        } else {
            return 1;
        }

    }

    /**
     * This is a comman method used to validate extra parameters of all apis
     * $rules: Here we need to pass name of fields which we have to validate
     * $request: Request $request
     */
    public function validateLatitude($lat) {
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
    }
    public function validateLongitude($long) {
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
    }
    public function validateTimezones($timezone) {
        $array_timezone = [
            '-12','-11','-10','-9.5','-9','-8','-7','-6','-5','-4.5','-4','-3.5','-3','-2','-1','0','1','2','3','3.1177777778','3.5','4','4.5','5','5.5','5.75','6','6.5','7','8','8.75','9','9.5','10','10.5','11','11.5','12','12.75','13','14'
        ];
        if(in_array($timezone, $array_timezone)){
            return 1;
        }else{
            return 0;
        }
        
    }
    
    public function validate_extra_params($rules, Request $request) {
        //$date = ($request->has('date')) ? $request->get('date') : '';
        //$startdate = ($request->has('startdate')) ? $request->get('startdate') : '';
        //$enddate = ($request->has('enddate')) ? $request->get('enddate') : '';

        $errcnt = 0;
        $msgs = array();

        //day
        if(($request->has('day') || in_array('day', $rules)) ) {
            if (($request->get('day') <= 0 || $request->get('day') > 31) || is_numeric($request->get('day')) === false || empty($request->get('day'))) {
                $msgs[] = 'Please enter valid day';
                $errcnt++;
            }
        }
        
        //month
        if(($request->has('month') || in_array('month', $rules))) {
            if (($request->get('month') <= 0 || $request->get('month') > 12) || is_numeric($request->get('month')) === false || empty($request->get('month'))) {
                $msgs[] = 'Please enter valid month';
                $errcnt++;
            }
        }

        //year
        if(($request->has('year') || in_array('year', $rules))) {
            if (($request->get('year') <= 1422 || $request->get('year') > 2823) || is_numeric($request->get('year')) === false || empty($request->get('year'))) {
                $msgs[] = 'Please enter valid year';
                $errcnt++;
            }
        }

        if($request->has('year')
        && $request->has('month')
        && $request->has('day')
        && is_numeric($request->get('year'))
        && is_numeric($request->get('month'))
        && is_numeric($request->get('day'))) {
            if(checkdate($request->get('month'),$request->get('day'),$request->get('year')) !== true){
                $msgs[] = 'Please enter valid day/month/year';
                $errcnt++;
            }
        }

        // Latitude Validation
        if(($request->has('lat') || in_array('lat', $rules)) 
        && ($request->get('lat') == "" || $this->validateLatitude($request->get('lat')) === 0)){
            $msgs[] = 'Please enter valid latitude';
            $errcnt++;
        }

        // Logitude Validation
        if(($request->has('lon') || in_array('lon', $rules)) 
        && ($request->get('lon') == "" || $this->validateLongitude($request->get('lon')) === 0)){
            $msgs[] = 'Please enter valid longitude';
            $errcnt++;
        }

        //timezone
        if( ($request->has('tzone') || in_array('tzone', $rules)) 
        && ($request->get('tzone') == "" || $this->validateTimezones($request->get('tzone')) === 0) ) {
            $msgs[] = 'Please enter valid timezone';
            $errcnt++;
        }

        

        /*//date
        if(in_array('date', $rules) && (strtotime($date) <= 0 || !self::validateDate($date))) {
            $msgs[] = 'Please enter date';
            $errcnt++;
        }

        //startdate
        if(in_array('startdate', $rules) && (strtotime($startdate) <= 0 || !self::validateDate($startdate))) {
            $msgs[] = 'Please enter start date';
            $errcnt++;
        }

        //enddate
        if(in_array('enddate', $rules) && (strtotime($enddate) <= 0 || !self::validateDate($enddate))) {
            $msgs[] = 'Please enter end date';
            $errcnt++;
        }
        

        //enddate
        if(in_array('enddate', $rules) && self::validateDate($startdate) && self::validateDate($enddate) && strtotime($enddate) < strtotime($startdate)) {
            $msgs[] = 'End date must be greater than or equal to start date';
            $errcnt++;
        }

        */

        if($errcnt > 0) {
            $resp = array('success' => 2, 'msg' => $msgs);
        }else{
            $resp = array('success' => 1, 'msg' => 'Validation successful');
        }

        return $resp;

    }

    /**
     * Method is used to validate date
     */
    public static function validateDate($date, $format = 'Y-m-d') {

        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && intval($d->format($format)) === $date;

    }

    /**
     * Method is used to execute swiss command using swedivine library
     */
    public function execute_swedivine_command($command) {

        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer' . env('SWE_TOKEN'),
        // ])->post('http://127.0.0.1:8000/api/get-swedivine-res', [
        //     'cmd' => $command,
        // ]);
        
        // $res = json_decode($response->body());
        // $result = $this->convert_object_to_array($res);

        // if($result['success'] == 1) { 
        //     return $result['res'];
        // }

        // return '';

        // $objSwetest = new Swedivine\Swedivine();
        // $objSwetest->setMaskPath(true);
        // $res = $objSwetest->query($command)->execute()->response();
        // unset($objSwetest);
        // return $res;

    }

    public function convert_object_to_array($data) {
       
        if(is_object($data)) {
            // Get the properties of the given object
            $data = get_object_vars($data);
        }
        if(is_array($data)) {
            //Return array converted to object
            return array_map([$this, 'convert_object_to_array'], $data);
        }
        else {
            // Return array
            return $data;
        }
        
    }

    public function getNextDate($date){
        return date('d.m.Y', strtotime('+1 day', strtotime($date)));
    }

    public function getPreviousDate($date)
    {
        return date('d.m.Y', strtotime('-1 day', strtotime($date)));
    }
    
    public function getTotalMinute($date)
    {
        $nextDate = $this->getNextDate($date);
        $todaySunrise = $this->getSunrise($date); 
    }

    public function getSunrise($date,$latlng)
    {   
        $sunrise = $this->swetest->query("-b$date -rise -geopos$latlng -g, -head")->execute()->response();
        return trim(explode(",",$sunrise['output'][1])[2]);
    }

    public function getSunset($date,$latlng)
    {   
        $sunset = $this->swetest->query("-b$date -rise -geopos$latlng -g, -head")->execute()->response();
        
        return trim(explode(",",$sunset['output'][1])[5]);
    }

    public function getNextSunrise($date,$latlng)
    {
        $nextDate = $this->getNextDate($date);
        $sunrise = $this->swetest->query("-b$nextDate -rise -geopos$latlng -g, -head")->execute()->response();
        return trim(explode(",",$sunrise['output'][1])[2]);
    }
    
    public function getDegreeWithTime($date,$latlng,$n,$skip)
    {  
        return $this->swetest->query("-b$date -ut".$this->getSunrise($date,$latlng)." -p01 -fPlT -geopos$latlng -n$n -s$skip -sid1 -g, -head")->execute()->response()['output'];
    }

    public function getDegreeWithCustomTime($date,$time,$latlng,$n,$skip)
    {  
        return $this->swetest->query("-b$date -ut".$time." -p01 -fPlT -geopos$latlng -n$n -s$skip -sid1 -g, -head")->execute()->response()['output'];
    }

    public function getSunriseDifferentInMinutes($startDate,$endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $diffInMinutes = $end->diffInMinutes($start);
        return $diffInMinutes;
    }

    public function getMinutesFromOffset($offset)
    {
        $carbon = Carbon::now($offset);
        $offset_in_minutes = $carbon->offset / 60;
    }

}