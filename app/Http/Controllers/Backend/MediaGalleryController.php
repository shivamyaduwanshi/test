<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaGallery;
use Spatie\MediaLibrary\Models\Media;
use Image;
use File;

class MediaGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mediaGalleries = MediaGallery::whereNull('deleted_at')->orderBy('id','desc')->paginate('10');
        return view('backend.mediagallery.index',compact('mediaGalleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.mediagallary.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [
            'file' => 'required|array',
        ];

        $request->validate($rules);

        if($request->hasFile('file')){
            foreach($request->file as $file){
                 $storeData = array();
                 $fileName = str_random(10) . time();
                 $storeData['name']          =  $fileName .'.'. $file->getClientOriginalExtension();
                 $storeData['original_name'] =  $file->getClientOriginalName();
                 $storeData['extension']     =  $file->getClientOriginalExtension();
                 $storeData['size']          =  $file->getSize();
                 $storeData['mime_type']     =  $file->getMimeType();
                 $storeData['uploaded_path'] =   '/uploads/' . date('Y') .'/'. date('m');
                 $path = public_path() . '/uploads/'. date('Y') .'/'. date('m');
                 $imageFullPath = $path . '/' . $storeData['name'];
                 File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                 try{
                     Image::make($file)->resize(300,300)->save($imageFullPath);
                     \DB::table('media_galleries')->insert($storeData);
                 }catch(\Execption $e){

                 }
            }
        }

        return back()->with('status',true)->with('message','Successfully uploaded images');

    }

    public function removeSpecialChar($str)
    {
        $res = str_replace( array( '\'', '"',
        ',' , ';', '<', '>' , '%' , '(' , ')' , '' ), '-', $str);
        // Returning the result 
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $mediaGallery = MediaGallery::find($request->id);
        $fileName     = $mediaGallery->name;
        $uploadedPath = $mediaGallery->uploaded_path;
        if($mediaGallery->delete()){
            File::delete( public_path() . '/uploads/' .  $uploadedPath .'/'. $fileName);
            return redirect()->route('backend.mediagallery.index')->with('status',true)->with('message',__('Successfully deleted image'));
        }else{
            return redirect()->route('backend.mediagallery.index')->with('status',false)->with('message',__('Failed delete image'));
        }
            
    }
}
