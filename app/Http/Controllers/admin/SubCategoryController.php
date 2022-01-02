<?php

namespace App\Http\Controllers\admin;

use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
class SubCategoryController extends Controller
{
    public function index(){
         $subCategories = SubCategory::with(['MainCategory'=> function($query){
            $query->select('name','id');
        }
        ])->get();
        return view('admin.subcategories.index',compact('subCategories'));
    }

    public function addEditSubCategory(Request $request,$id=null){
        try {

            if($id==""){
                $title = "اضافه قسم فرعي";
                //add category functionality
                $subcategorydata = array();
                $category = new SubCategory;
            }else{
                 $title = "تعديل قسم فرعي";
                 $subcategorydata = SubCategory::find($id);
                 $category = SubCategory::find($id);
                 //Edit Category functionality
             }




             if($request->isMethod('post')){
                 $data = $request ->all();
                // return $data;

                $category->name = $data['name'];
                $category->parent_id = $data['parent_id'];
                $category->save();

                return redirect()->route('admin.subcategories')->with(['success' => 'تم التعديل بنجاح']);
             }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }

        //$default_lang = get_default_lang();
        $getMainCategories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.subcategories.add_edit_category',compact('title','getMainCategories','subcategorydata'));
    }

    public function destroy($id){
        try {

            $deleteSubCategory = SubCategory::find($id);
            $deleteSubCategory ->delete();

            return redirect()->route('admin.subcategories')->with(['success' => 'تم الحذف بنجاح ']);

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);

        }
    }
}
