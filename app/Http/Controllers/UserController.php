<?php

namespace App\Http\Controllers;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class UserController extends Controller
{

    function LoginPage()
    {
        return Inertia::render('LoginPage');
    }

    function RegistrationPage()
    {
        return Inertia::render('RegistrationPage');
    }

    function ResetPasswordPage()
    {
        return Inertia::render('ResetPasswordPage');
    }

    function SendOtpPage()
    {
        return Inertia::render('SendOtpPage');
    }
    function VerifyOtpPage()
    {
        return Inertia::render('VerifyOtpPage');
    }



    function ProfilePage(Request $request)
    {
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return Inertia::render('ProfilePage',['list'=>$user]);
    }


    function UserRegistration(Request $request){

        try {
            $email=$request->input('email');
            $name=$request->input('name');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::create([
                'name'=>$name,
                'email'=>$email,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            $data =['message'=>'Registration Successful','status'=>true,'error'=>''];
            return  redirect()->route('RegistrationPage')->with($data);
        }

        catch (Exception $e) {
            $data =['message'=>'Registration Fail','status'=>false,'error'=>$e->getMessage()];
            return  redirect()->route('RegistrationPage')->with($data);
        }

    }


    function UserLogin(Request $request){

        $result= User::where($request->input())->count();
        $id=User::where($request->input())->select('id')->first();

        if($result==1){

            $email=$request->input('email');
            $request->session()->put('email',$email);
            $request->session()->put('user_id',$id['id']);

            $data =['message'=>'Login Successful','status'=>true,'error'=>''];
            return  redirect()->route('DashboardPage')->with($data);
        }
        else{
            $data =['message'=>'Login Fail','status'=>false];
            return  redirect()->route('LoginPage')->with($data);
        }


    }


    function UserLogout(Request $request){
        $request->session()->flush();
        return redirect()->route('LoginPage');
    }



    function SendOTPCode(Request $request){
        $email=$request->input('email');
        $otp=rand(1000,9999);
        $count=User::where('email','=',$email)->count();
        if($count==1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email','=',$email)->update(['otp'=>$otp]);
            $data =['message'=>'Request Successful','status'=>true,'error'=>''];
            $request->session()->put('email',$email);
            return  redirect()->route('SendOtpPage')->with($data);
        }
        else{
            $data =['message'=>'Request Successful','status'=>false,'error'=>''];
            return  redirect()->route('SendOtpPage')->with($data);
        }
    }


    function VerifyOTP(Request $request){
        $email=$request->session()->get('email','default');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
            ->where('otp','=',$otp)->count();

        if($count==1){
            User::where('email','=',$email)->update(['otp'=>'0']);
            $request->session()->put('otp_verify','yes');
            $data =['message'=>'Request Successful','status'=>true,'error'=>''];
            return  redirect()->route('VerifyOtpPage')->with($data);
        }
        else{
            $data =['message'=>'Request Fail','status'=>false,'error'=>''];
            return  redirect()->route('VerifyOtpPage')->with($data);
        }
    }


    function ResetPassword(Request $request){

        try{
            $email=$request->session()->get('email','default');
            $password=$request->input('password');
            $otp_verify=$request->session()->get('otp_verify','default');

            if($otp_verify==="yes"){
                User::where('email','=',$email)->update(['password'=>$password]);
                $data =['message'=>'Request Successful','status'=>true];
                $request->session()->flush();
                return  redirect()->route('ResetPasswordPage')->with($data);
            }
            else{
                $data =['message'=>'Request Fail','status'=>false];
                return  redirect()->route('ResetPasswordPage')->with($data);
            }



        }catch (Exception $e){
            $data =['message'=>'Request Fail','status'=>false,'error'=>$e->getMessage()];
            return  redirect()->route('ResetPasswordPage')->with($data);
        }

    }






    function UpdateProfile(Request $request){
        try{
            $email=$request->input('email');
            $name=$request->input('name');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'name'=>$name,
                'email'=>$email,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            $data =['message'=>'Request Success','status'=>true];
            return  redirect()->route('ProfilePage')->with($data);
        }catch (Exception $e){
            $data =['message'=>'Request Success','status'=>true,'error'=>$e->getMessage()];
            return  redirect()->route('ProfilePage')->with($data);
        }
    }
}

