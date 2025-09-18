<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{


    public function CategoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $list = Category::where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage', ['list' => $list]);
    }


    public function CategorySavePage(Request $request)
    {
        $category_id = $request->query('id');
        $user_id = $request->header('id');
        $list = Category::where('id', $category_id)->where('user_id', $user_id)->first();
        return Inertia::render('CategorySavePage', ['list' => $list]);
    }





    // API endpoint for fetching categories
    public function CategoryList(Request $request)
    {
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return response()->json($categories);
    }




    public function CategoryCreate(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Category::create([
            'name' => $request->input('name'),
            'user_id' => $user_id
        ]);
        return redirect()->route('CategoryPage')->with(['message' => 'Create Successful', 'status' => true]);
    }


    public function CategoryDelete(Request $request)
    {
        $category_id = $request->id;
        $user_id = $request->header('id');
        try {
            $deleted = Category::where('id', $category_id)->where('user_id', $user_id)->delete();
            if ($deleted) {
                $data = ['message' => 'Delete Successful', 'status' => true, 'error' => ''];
            } else {
                $data = ['message' => 'Category Not Found', 'status' => false, 'error' => ''];
            }
        } catch (Exception $e) {
            $data = ['message' => 'Delete Failed', 'status' => false, 'error' => $e->getMessage()];
        }
        return redirect()->route('CategoryPage')->with($data);
    }


    public function CategoryByID(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->first();
    }


    public function CategoryUpdate(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        try {
            $updated = Category::where('id', $category_id)->where('user_id', $user_id)
                ->update(['name' => $validated['name']]);
            if ($updated) {
                $data = ['message' => 'Update Successful', 'status' => true, 'error' => ''];
            } else {
                $data = ['message' => 'Category Not Found', 'status' => false, 'error' => ''];
            }
        } catch (Exception $e) {
            $data = ['message' => 'Update Failed', 'status' => false, 'error' => $e->getMessage()];
        }
        return redirect()->route('CategoryPage')->with($data);
    }
}
