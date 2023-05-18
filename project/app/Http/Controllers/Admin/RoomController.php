<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Room;
use App\RoomType;
use App\Facility;
use App\RoomFacility;
use App\Asset;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;


class RoomController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $controller = 'room';

    private function title(){
        return __('main.room_list');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->controller.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        $room_type = RoomType::where("status","=", 0)->get();

        return view('backend.'.$this->controller.'.list', compact('room_type'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
        }        

        $room = new Room;
        $datas = $room->get_data();

        return DataTables::of($datas)
        ->filter(function ($query) use ($request) {
            $query->when($request->has('search.value'), function ($q) use ($request) {
                $value = $request->input('search.value');
                $q->where(function ($query) use ($value) {
                    $query->where('rooms.name', 'like', "%$value%")
                        ->orWhere('rooms.status', 'like', "%$value%");
                });
            });
        })
        ->addColumn('action', function ($data) {
            // add your action column logic here
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function validate_data(Request $request, $id = null){
        $rules = [
            'name' => 'required',
            'area' => 'required',
            'price' => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
    
        return $validator;
    }    
    
    public function create(Request $request){
        if (!Auth::user()->can($this->controller.'-create')){
            return $this->unauthorizedAccessModule();
        }  

        $validator = $this->validate_data($request);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }

        // set room assets
        $asset = Asset::upload($request->file('asset_id'), "room");
        if (!empty($asset) && $asset['status'] == 'error') {
            return $this->badRequest($asset['message']);
        }
        
        $data = $request->all();
        if (!empty($asset['data'])) {
            $data['asset_id'] = $asset['data']->id;
        }

        // Create the Room
        $room = Room::create($data);

        $facilities = json_decode($data['facility']);
        if (!empty($facilities)) {
            foreach ($facilities as $facility) {
                // Find the Facility model by name (case-insensitive) and retrieve its ID
                $facilityModel = Facility::whereRaw('LOWER(name) = ?', strtolower($facility->value))->first();
                
                if ($facilityModel) {
                    RoomFacility::create([
                        'room_id' => $room->id,
                        'facility_id' => $facilityModel->id,
                    ]);
                }
            }
        }

        return $this->created($room, null);
    }

    public function update(Request $request, $id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }        

        $validator = $this->validate_data($request, $id);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }   

        $room = Room::find($id);
        if (!$room) {
            return $this->errorNotFound(null);
        }

        // set room assets
        $asset = Asset::upload($request->file('asset_id'), "product");
        if (!empty($asset) && $asset['status'] == 'error') {
            return $this->badRequest($asset['message']);
        }
        
        $data = $request->all();
        if (!empty($asset['data'])) {
            $data['asset_id'] = $asset['data']->id;
        }
    
        // Update the Room attributes
        $room->fill($data);
        $room->save();
    
        // Delete existing RoomFacility records for the room
        RoomFacility::where('room_id', $room->id)->delete();
    
        // Create new RoomFacility pivot records
        $facilities = json_decode($data['facility']);
        if (!empty($facilities)) {
            foreach ($facilities as $facility) {
                // Find the Facility model by name (case-insensitive) and retrieve its ID
                $facilityModel = Facility::whereRaw('LOWER(name) = ?', strtolower($facility->value))->first();
                
                if ($facilityModel) {
                    RoomFacility::create([
                        'room_id' => $room->id,
                        'facility_id' => $facilityModel->id,
                    ]);
                }
            }
        }
    
        return $this->ok($room, null);
    }
    
    public function detail($id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }  
        $room = new Room;
        $datas = $room->get_data();

        $res = $datas->find($id);
        if(!$res){
            return $this->errorNotFound(null);
        }    

        return $this->ok($res, null);
    }

    public function delete($id){
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Room::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }
        if (!empty($res->asset_id)) {
            Asset::remove($res->asset_id);
        }
        $res->delete();
        
        return $this->deleted("Data deleted successfully");
    }

    public function delete_batch(Request $request) {
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $ids = $request->input('id');
        foreach ($ids as $id) {
            $res = Room::find($id);
            if (!$res) {
                return $this->errorNotFound(null);
            }
            if (!empty($res->asset_id)) {
                Asset::remove($res->asset_id);
            }
            $res->delete();
        }

        return $this->deleted("Data deleted successfully");
    }

}
