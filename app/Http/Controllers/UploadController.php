<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use Response;
use Auth;
use Storage;
use Image;

class UploadController extends Controller
{


    public function index(Request $request){


        $all_uploads =  Upload::query();
        $search = null;
        $sort_by = null;

        if ($request->search != null) {
            $search = $request->search;
            $all_uploads->where('file_original_name', 'like', '%'.$request->search.'%');
        }


        $all_uploads = $all_uploads->paginate(60)->appends(request()->query());


        return view('uploaded_files.index', compact('all_uploads', 'search', 'sort_by'));
    }

    public function create(){
        return 
            view('uploaded_files.create');
    }


    public function show_uploader(Request $request){
        return view('uploader.aiz-uploader');
    }
    
    public function upload(Request $request){
        $type = array(
            "jpg"=>"image",
            "jpeg"=>"image",
            "png"=>"image",
            "svg"=>"image",
            "webp"=>"image",
            "gif"=>"image",
            "jfif"=>"image",
            );

        if($request->hasFile('aiz_file')){
            $upload = new Upload;
            $extension = strtolower($request->file('aiz_file')->getClientOriginalExtension());

            if(isset($type[$extension])){
                $upload->file_original_name = null;
                $arr = explode('.', $request->file('aiz_file')->getClientOriginalName());
                for($i=0; $i < count($arr)-1; $i++){
                    if($i == 0){
                        $upload->file_original_name .= $arr[$i];
                    }
                    else{
                        $upload->file_original_name .= ".".$arr[$i];
                    }
                }

                $path = $request->file('aiz_file')->move(public_path('uploads/all'), rand(500000,90000000000).$upload->file_original_name.".".$extension);
                $size = 50000;

                // Return MIME type ala mimetype extension
                $finfo = finfo_open(FILEINFO_MIME_TYPE); 

                // Get the MIME type of the file
                $file_mime = finfo_file($finfo,$path);

                $upload->extension = $extension;
                $upload->file_name = str_replace(public_path(),"",$path);
                $upload->type = $type[$upload->extension];
                $upload->file_size = $size;
                $upload->save();
            }
            return '{}';
        }
    }

    public function get_uploaded_files(Request $request)
    {
        $uploads = Upload::whereNotNull('id');
        if ($request->search != null) {
            $uploads->where('file_original_name', 'like', '%'.$request->search.'%');
        }
      
        return $uploads->paginate(60)->appends(request()->query());
    }

    public function destroy(Request $request,$id)
    {
        $upload = Upload::findOrFail($id);

        
        try{
            if(env('FILESYSTEM_DRIVER') == 's3'){
                Storage::disk('s3')->delete($upload->file_name);
            }
            else{
                unlink(public_path().'/'.$upload->file_name);
            }
            $upload->delete();
            flash(translate('File deleted successfully'))->success();
        }
        catch(\Exception $e){
            $upload->delete();
            flash(translate('File deleted successfully'))->success();
        }
        return back();
    }

    public function get_preview_files(Request $request){
        $ids = explode(',', $request->ids);
        $files = Upload::whereIn('id', $ids)->get();
        return $files;
    }

    //Download project attachment
    public function attachment_download($id)
    {
        $project_attachment = Upload::find($id);
        try{
           $file_path = public_path($project_attachment->file_name);
            return Response::download($file_path);
        }catch(\Exception $e){
            flash(translate('File does not exist!'))->error();
            return back();
        }

    }
    //Download project attachment
    public function file_info(Request $request)
    {
        $file = Upload::findOrFail($request['id']);

        return view('uploaded_files.info',compact('file'));
    }

}
