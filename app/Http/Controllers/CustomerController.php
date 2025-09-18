<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use Exception;
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


    public function CustomerSavePage(Request $request)
    {
        $customer_id = $request->query('id');
        $user_id = $request->header('id');

        $customer = Customer::where('id', $customer_id)
            ->where('user_id', $user_id)
            ->first();

        return Inertia::render('CustomerSavePage', [
            'customer' => $customer
        ]);
    }

    public function CustomerCreate(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
        ]);
        Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id
        ]);
        $data = ['message' => 'Customer Created Successfully', 'status' => true];
        return redirect()->route('CustomerPage')->with($data);
    }

    function CustomerList(Request $request){
        $user_id=$request->header('id');
        return Customer::where('user_id',$user_id)->get();
    }

    function CustomerDelete(Request $request){
        try {
            $customer_id=$request->id;
            $user_id=$request->header('id');
            Customer::where('id',$customer_id)->where('user_id',$user_id)->delete();
            $data =['message'=>'Customer Deleted Successfully','status'=>true];
            return  redirect()->route('CustomerPage')->with($data);
        } catch (Exception $e) {
            $data =['message'=>'Something Went Wrong','status'=>false,'error'=>$e->getMessage()];
            return  redirect()->route('CustomerPage')->with($data);
        }
    }

    function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->first();
    }

    public function CustomerUpdate(Request $request)
    {
        $customer_id = $request->input('id');
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
        ]);
        Customer::where('id', $customer_id)->where('user_id', $user_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);
        $data = ['message' => 'Customer Updated Successfully', 'status' => true];
        return redirect()->route('CustomerPage')->with($data);
    }
}
