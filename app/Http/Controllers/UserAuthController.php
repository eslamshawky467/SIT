<?php
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Exception;
class UserAuthController extends Controller

{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|exists:users,email' ,
            'password' => 'required|string',
        ] ,[
           'email.required'=>'email required',
           'email.unique'=>'email has already taken',
           'password.required'=>'password required',
         ]);

        if ($validator->fails()) {

            return response()->json(['message'=>$validator->errors()->first()],422);
        }
try {
    if (!$token = auth()->guard('web')->attempt($validator->validated())) {
        return response()->json(['message' =>trans('auth.login.failed')], 401);
    }
    return $this->respondWithToken($token);
   }catch(\Exception $e){

    return $this->apiresponse('[]',$e->getMessage(),500);
    }
  }

  public function register(Request $request){
      $validator = Validator::make($request->all(), [
          'user_name'=> 'required|string|max:20',
          'email' =>'required|email|unique:users',
          'password' => 'required|string|min:8',
          'phone'=>'required',
      ],[
          'email.email' => 'must be email',
          'email.required' =>'email required' ,
          'email.unique' => 'email is already taken',
          'password.required' => 'password required',
          'user_name.required' => 'user name required',
          'phone.required'=>'phone required',
      ]);
      if ($validator->fails()) {

          return response()->json(['message'=>$validator->errors()->first(),'status'=> 422]);
      }
try {
    $user = User::create(array_merge(
        $validator->validated(),
        ['password' => bcrypt($request->password)],
    ));
    return response()->json([
        'message' => 'User successfully registered',
        'user' => $user
    ], 200);
}catch(\Exception $e){

    return $this->apiresponse('[]',$e->getMessage(),500);
    }
  }
  protected function respondWithToken($token)
  {

      return response()->json([
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => auth()->guard('web')->factory()->getTTL() * 60,
          'user' => auth('web')->user(),
      ]);
  }

  public function logout()
  {
      auth('web')->logout();
      return response()->json(['message' =>'User Successfully Logged Out']);
  }
  public function me()
  {
      return response()->json(auth('web')->user());
  }
}
