<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{


    function CustomerPage(Request $request)
    {
        $user_id=$request->header('id');
        $list= Customer::where('user_id',$user_id)->get();
        return Inertia::render('CustomerPage',['list'=>$list]);
    }



    function CustomerCreate(Request $request){
        $user_id=$request->header('id');
        return Customer::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'user_id'=>$user_id
        ]);
    }

    function CustomerList(Request $request){
        $user_id=$request->header('id');
        return Customer::where('user_id',$user_id)->get();
    }

    function CustomerDelete(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->delete();
    }

    function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->first();
    }

    function CustomerUpdate(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
        ]);
    }
}
