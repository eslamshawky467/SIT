<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use ApiResponseTrait;
    public function index(){
        try{
        $employees=Employee::paginate(5);
        if($employees){
            return $this->apiresponse($employees,'Successful Data Retrived',200);
        }
        return $this->apiResponse('[]','No Data Found',404);

    }catch(\Exception $e){

        return $this->apiresponse('[]',$e->getMessage(),500);
        }
    }


    public function store(Request $request){
            $validator = Validator::make($request->all(), [
                'first_name'=>'required|string',
                'email'=>'required|email|unique:employees',
                'company_id' => 'required',
                'phone' => 'required|unique:employees',
                'last_name'=>'required|string',
            ],[
                'email.email' => 'email must be email',
                'email.required' =>'email is required' ,
                'email.unique' => 'email has already taken',
                'phone.required' => 'phone is required',
                'first_name.required' => 'first name required',
                'last_name.required' => 'last name required',
                'company_id.required'=>'company_id required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message'=>$validator->errors()->first()],422);
            }
            try{
            DB::beginTransaction();
            Employee::create([
                'first_name'=>$request->first_name,
                'email'=>$request->email,
                'company_id'=>$request->company_id,
                'last_name'=>$request->last_name,
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
            'first_name'=>'required|string',
            'email'=>'required|email|unique:employees',
            'company_id' => 'required',
            'phone' => 'required|unique:employees',
            'last_name'=>'required|string',
        ],[
            'email.email' => 'email must be email',
            'email.required' =>'email is required' ,
            'email.unique' => 'email has already taken',
            'phone.required' => 'phone is required',
            'first_name.required' => 'first name required',
            'last_name.required' => 'last name required',
            'company_id.required'=>'company_id required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first()],422);
        }
        try{
        DB::beginTransaction();
        $employee=Employee::findOrFail($request->id);
        $employee->first_name=$request->first_name;
        $employee->email=$request->email;
        $employee->last_name=$request->last_name;
        $employee->company_id=$request->company_id;
        $employee->phone=$request->phone;
        $employee->save();
        DB::commit();
        return $this->apiresponse('[]','Successful Data Updated',200);
    }catch(\Exception $e){
        DB::rollback();
        return $this->apiresponse('[]',$e->getMessage(),500);
        }
    }

    public function destroy(Request $request){
try {
    Employee::findOrFail($request->id)->delete();
    return $this->apiresponse('[]', 'Successful Data Deleted', 200);
}catch(\Exception $e){
    DB::rollback();
    return $this->apiresponse('[]',$e->getMessage(),500);
    }
    }
}
