<?php

namespace App\Http\Controllers\admin;

use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    public function index(){
        $subCategories = SubCategory::select();
        return view('admin.subcategories.index',compact('subCategories'));
    }

    public function addEditSubCategory(Request $request,$id=null){
        if ($id=="") {
            $title = "Add Sub Category";
            //add category functionality
            $category = new SubCategory;
         }else {
             $title = "Edit Sub Category";
             //Edit Category functionality
         }
         if($request->isMethod('post')){
             $data = $request ->all();
            // return $data;

            $category->name = $data['name'];
            $category->parent_id = $data['parent_id'];
            $category->save();
         }
        $getMainCategories = MainCategory::get();
        return view('admin.subcategories.add_edit_category',compact('title','getMainCategories'));
    }
}
