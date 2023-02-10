<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use ApiResponseTrait;
    public function index(){
        try{
        $users=User::paginate(5);
        if($users){
            return $this->apiresponse($users,'Successful Data Retrived',200);
        }
        return $this->apiResponse('[]','No Data Found',404);
    }catch(\Exception $e){

	return $this->apiresponse('[]',$e->getMessage(),500);
	}
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required|unique:users',
        ],[
            'email.email' => 'must be email',
            'email.required' =>'email is required' ,
            'email.unique' =>'email has already taken',
            'password.required' => 'password required',
            'user_name.required' => 'user name required',
            'phone.required'=>'phone required',
            'phone.unique' =>'phone has already taken',
        ]);
            if ($validator->fails()) {
                return response()->json(['message'=>$validator->errors()->first()],422);
            }
            try{
                DB::beginTransaction();
            User::create([
                'user_name'=>$request->user_name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'phone'=>$request->phone,
            ]);
            DB::commit();

            return $this->apiresponse('[]','Successful Data Stored',200);
        }catch(\Exception $e){
            DB::rollback();
            return $this->apiresponse('[]',$e->getMessage(),500);
            }
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required|unique:users',
        ],[
            'email.email' => 'must be email',
            'email.required' =>'email is required' ,
            'email.unique' =>'email has already taken',
            'password.required' => 'password required',
            'user_name.required' => 'user name required',
            'phone.required'=>'phone required',
            'phone.unique' =>'phone has already taken',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first()],422);
        }
        try{
        DB::beginTransaction();
        $users=User::findOrFail($request->id);
        $users->user_name=$request->user_name;
        $users->email=$request->email;
        $users->password=Hash::make($request->password);
        $users->phone=$request->phone;
        $users->save();
        DB::commit();
        return $this->apiresponse('[]','Successful Data Updated',200);
    }catch(\Exception $e){
        DB::rollback();
        return $this->apiresponse('[]',$e->getMessage(),500);
        }
    }

    public function destroy(Request $request){
    try {
        User::findOrFail($request->id)->delete();
        return $this->apiresponse('[]', 'Successful Data Deleted', 200);
    }
    catch(\Exception $e){
        DB::rollback();
        return $this->apiresponse('[]',$e->getMessage(),500);
        }
}
}
