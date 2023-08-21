<?php

namespace App\Http\Middleware;

use Closure;


class CheckPasswordAndPin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$type)
    {   
		if($type=="password"){
			$validated = \Validator::make($request->all(),[
				'password' => 'sometimes|required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[?!@$%^&*-]).{6,12}$/'
				],
				[
				'password.required'=>'Please Enter Password',
				'password.regex'=> 'Your password must be 6 to 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character from ?!@$%^&*-' // custom message       
				]
			);
		}else if($type=="tpin"){
			$arrayValue=array('1234','1111','2222','3333','4444','5555','6666','7777','8888','9999','0000');
		
			$validated = \Validator::make($request->all(),[
				'pin' => 'required|numeric|min:4|not_in:'.implode(',',$arrayValue),
				],
				[
				'pin.required'=>'Please Enter Tpin',
				'pin.not_in'=> 'Your tpin must be Strong,tpin must be random number not like this 1111,1234,0000 ' // custom message       
				]
			);

		}
		
		if($validated->fails())
		{   foreach($validated->errors()->messages() as $key=>$val){
				$msg[$key]=$val[0];
			}
			return response()->json(['errors' => $msg], 422);
		}
		
        return $next($request);
    }
}
