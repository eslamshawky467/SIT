<?php
namespace App\Repository;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Repository\CompanyRepositryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\traits\ApiResponseTrait;
class CompanyRepository implements CompanyRepositryInterface{
use ApiResponseTrait;
    public function index(){
        try{
        $companies=Company::paginate(5);
        if($companies){
            return $this->apiresponse($companies,'Successful Data Retrived',200);
        }
        return $this->apiResponse('[]','No Data Found',404);
    }catch(\Exception $e){

        return $this->apiresponse('[]',$e->getMessage(),500);
        }
    }

    public function store(Request $request){
            $validator = Validator::make($request->all(), [
                'name'=>'required|string',
                'email'=>'required|email|unique:companies',
                'website' => 'required',
            ],[
                'email.email' => 'email must be email',
                'email.required' =>'email is required' ,
                'email.unique' => 'email has already taken',
                'name.required' => 'name required',
                'website.required'=>'website required',
            ]);


            if ($validator->fails()) {
                return response()->json(['message'=>$validator->errors()->first()],422);
            }
            try{
                DB::beginTransaction();
            Company::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'website'=>$request-> website,
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
            'name'=>'required|string',
            'email'=>'required|email|unique:companies',
            'website' => 'required',
        ],[
            'email.email' => 'email must be email',
            'email.required' =>'email is required' ,
            'email.unique' => 'email has already taken',
            'name.required' => 'name required',
            'website.required'=>'website required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first()],422);
        }
        try{
        DB::beginTransaction();
        $company=Company::findOrFail($request->id);
        $company->name=$request->name;
        $company->email=$request->email;
        $company->website=$request->website;
        $company->save();
        DB::commit();
        return $this->apiresponse('[]','Successful Data Updated',200);
    }catch(\Exception $e){
        DB::rollback();
        return $this->apiresponse('[]',$e->getMessage(),500);
        }
    }

    public function destroy(Request  $request){

try {
    Company::findOrFail($request->id)->delete();
    return $this->apiresponse('[]', 'Successful Data Deleted', 200);
}catch(\Exception $e){
    DB::rollback();
    return $this->apiresponse('[]',$e->getMessage(),500);
    }
    }

}
