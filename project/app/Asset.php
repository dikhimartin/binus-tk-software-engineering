<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use File;

class Asset extends Model
{
    Use Uuid;

    protected $table = 'assets';

    protected $fillable = [
        'title', 'file_name','file_size','original_file_name','absolute_path','relative_path','description','status'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';


    public static function upload($file = null, $subFolder = null, $id = null){
        if ($file instanceof UploadedFile) {
            if ($id) {
                Asset::remove($id);
            }
            $original_file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getSize();
            $filename = 'P-' . time() . '-' . Str::random(10) . '.' . $file_ext;
    
            $path = config('app.root_assets_path');
            if ($subFolder) {
                $path = $path .'/' . $subFolder;
            }
            $destination_path = './' . $path . '/';
            $relative_path = $path . '/' . $filename;
            $absolute_path = config('app.url') . '/' . $path . '/' . $filename;
            if (!$file->move($destination_path, $filename)) {
                return [
                    'status' => 'error',
                    'message' => 'Cannot upload file'
                ];
            }
    
            $data = new Asset();
            $data->title = "generated";
            $data->file_name = $filename;
            $data->file_size = $file_size;
            $data->original_file_name = $original_file_name;
            $data->absolute_path = $absolute_path;
            $data->relative_path = $relative_path;
            $data->status = 1;
            $data->save();
            return [
                'status' => 'success',
                'data' => $data
            ];
        }
    }
    
    public static function remove($id){
        $asset = Asset::find($id);
        if(!empty($asset)){
            File::delete($asset->relative_path);
            $asset->delete();
        }
    }

}