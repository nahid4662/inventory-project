<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{


    function ProductPage(Request $request)
    {
        $user_id=$request->header('id');
        $list=Product::where('user_id',$user_id)->with('category')->get();
        return Inertia::render('ProductPage',['list'=>$list]);
    }

    public function ProductSavePage(Request $request)
    {
        $product_id = $request->query('id');
        $user_id = $request->header('id');

        $product = Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->with('category')
            ->first();

        $categories = Category::where('user_id', $user_id)->get();

        return Inertia::render('ProductSavePage', [
            'product' => $product,
            'categories' => $categories
        ]);
    }


    public function CreateProduct(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
        ]);
        Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'category_id' => $request->input('category_id'),
            'user_id' => $user_id
        ]);
        $data = ['message' => 'Product Created Successfully', 'status' => true];
        return redirect()->route('ProductPage')->with($data);
    }

    function DeleteProduct(Request $request)
    {
        try {
            $product_id=$request->id;
            $user_id=$request->header('id');
            Product::where('id',$product_id)->where('user_id',$user_id)->delete();
            $data =['message'=>'Delete Successful','status'=>true,'error'=>''];
            return  redirect()->route('ProductPage')->with($data);
        }catch (Exception $e){
            $data =['message'=>'Delete Fail','status'=>false,'error'=>$e->getMessage()];
            return  redirect()->route('ProductPage')->with($data);
        }

    }

    function ProductByID(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        return Product::where('id',$product_id)->where('user_id',$user_id)->first();


    }

    function ProductList(Request $request)
    {
        $user_id=$request->header('id');
        return Product::where('user_id',$user_id)->get();
    }

    public function UpdateProduct(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
        ]);
        Product::where('id', $product_id)->where('user_id', $user_id)->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'category_id' => $request->input('category_id')
        ]);
        $data = ['message' => 'Product Updated Successfully', 'status' => true, 'error' => ''];
        return redirect()->route('ProductPage')->with($data);
    }
}
