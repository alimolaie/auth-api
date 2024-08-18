<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController as BaseController;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function register(Request $request)

    {
        $validator = Validator::make($request->all(), [

            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',

        ]);



        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());

        }



        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] =  $user->createToken('auth')->accessToken;
        Auth::login($user);
        try{

            $sms = Melipayamak::sms();
            $code=rand(1000,9999);
            $to = $request->mobile;
            $from = '50002710016347'; //your sms number
            $text = 'your verify code is: '.$code;
            $response = $sms->send($to,$from,$text);
            User::where('id',Auth::id())->update(['identification_sms'=>$code]);
        }catch(Exception $e){
            echo $e->getMessage();
        }


        return $this->sendResponse($success, 'User register successfully please verify your mobile.');

    }

   public function confirmMobile(Request $request)
    {
        $mobile=$request->mobile;
        $code=$request->code;
        $user=User::whereMobile($mobile)->first();
        if ($user->identification_sms==$code){
            $user->update(['status'=>1]);
            echo "you are registered";
        }
        else
            echo "this code is wrong";
    }

    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request)

    {
        $credentials = [
            'mobile' => $request['mobile'],
            'password' => $request['password'],
            'status' =>1,
        ];

        if(Auth::attempt($credentials)){

            $user = Auth::user();
            $success['token'] =  $user->createToken('auth')->accessToken;

            return $this->sendResponse($success, 'User login successfully.');

        }

        else{

            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);


        }

    }
}
