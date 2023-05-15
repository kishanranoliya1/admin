<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Upload;
use Response;
use Auth;
use Storage;
use Image;
use DB;

class UploadController extends Controller
{
    public function index(Request $request){
        $baseurl = url('/') . '/';
        $all_uploads =  Upload::select('*', DB::raw("CONCAT('$baseurl', file_name) AS file_name"))->pluck('file_name')->toArray();        
        if (count($all_uploads) > 0) {
            return response()->json(
                [
                    'data' => $all_uploads,
                    'success' => true,
                    'message' => 'Upload list successfully.',
                ], 200);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Upload list empty.',
                ], 204);
        } 
    }

}
