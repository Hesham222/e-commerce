<?php

namespace App\Http\Controllers\admin;



use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\MainCategoreyRequest;

class MainCategoryController extends Controller
{
    public function index(){
         $default_lang = get_default_lang();
       $categories = MainCategory::where('translation_lang',$default_lang)->selection() ->get();
        return view('admin.maincategories.index',compact('categories'));
    }

    public function create(){
        return view('admin.maincategories.create');
    }
    //كل اللفكره هنا انه عايز ع حسب ال ديفولت لانجوتش ويعرض الديفولت بردو
    public function store(MainCategoreyRequest $request){

        try {
            $main_categorries = collect($request -> categorey);

            $filter = $main_categorries ->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

            $default_categorey = array_values($filter -> all()) [0];
            $filePath ="";
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request -> photo);
            }

            DB::beginTransaction();
            // ده بيشاور ع اوبجكت فطالما احنا هنتعامل مع الاندكس ف مش هينفع نحطه  ->
            $default_categorey_id = MainCategory::insertGetId([
            'translation_lang' => $default_categorey ['abbr'],
            'translation_of' => 0,
            'name' => $default_categorey ['name'],
            'slug' => $default_categorey ['name'],
            'photo' => $filePath
        ]);

            $categories = $main_categorries ->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });

            if (isset($categories) && $categories -> count()) {
                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [

                    'translation_lang' => $category ['abbr'],
                    //بص انا ف الترانسليشن اوف باخد الاي دي اللي جبته من اللغه الديفولت واحطه ف اللغات التانيه عشان يبقي بيشير عليها
                    'translation_of' => $default_categorey_id,
                    'name' => $category ['name'],
                    'slug' => $category['name'],
                    'photo' => $filePath
                ];
                }
                MainCategory::insert($categories_arr);

            }

            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح ']);
        } catch(\Exception $ex){

            DB::rollback();

           return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);

            }

    }
    public function edit($id)
    {
        //get specific categories and its translations
         $mainCategory = MainCategory::with('categories')
            ->selection()
            ->find($id);

        if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

        return view('admin.maincategories.edit', compact('mainCategory'));
    }


    public function update($id, MainCategoreyRequest $request)
    {


        try {
            $main_category = MainCategory::find($id);

            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            // update date

            $category = array_values($request->categorey) [0];

            if (!$request->has('categorey.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            MainCategory::where('id', $id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // save image

            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
                MainCategory::where('id', $id)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }


            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }
    public function destroy($id){
        try {
            $delete_category = MainCategory::find($id);
            if (!$delete_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
            }
//لما يكون قسم ليه متااجر مينفعش تمسحه عادي كده لازن تتاكد هو عنده نتاجر ولا لاء
            $vendors = $delete_category -> vendors();
            if(isset($vendors) && $vendors -> count() > 0){
                return redirect()->route('admin.maincategories')->with(['error' => 'لا يمكن حذف هذا القسم ']);
            }
            // مبتمسحش الا من اول  فولدر الصور وكل الصور عندي متخزنه بالمسار من اوله ف بستخدم ال س ت ر عشان اقوله يقطع من اول الاسيت ل  unlink
            $image =  Str::after($delete_category -> photo,'assets/');

            $img = base_path('assets/'.$image);// بتجيب مسار الصوره ع اللابتوب عندي
            unlink($img);
            //delete translation languages
            $delete_category -> categories() ->delete();
            $delete_category ->delete();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف بنجاح ']);
        }catch(\Exception $ex){
           // return $ex;
            return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);
        }
    }

    public function changeStatus($id){
        try{

            $main_category = MainCategory::find($id);

            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            $status = $main_category -> active == 0 ? 1 :0;
            $main_category ->update(['active' => $status]);

            return redirect()->route('admin.maincategories')->with(['success' => 'تم تحديث الحاله بنجاح ']);

        }catch(\Exception $ex){
            return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);
        }
    }




//     public function edit($id){
//         $categories = MainCategory::selection()->find($id);
//          if(!$categories){
//             return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);
//          }
//          return view('admin.maincategories.edit',compact('categories'));
//     }
//     // public function update($mainCat_id, MainCategoreyRequest $request)
//     // {
//     //   // return $request;

//     //     try {
//     //         $main_category = MainCategory::find($mainCat_id);

//     //         if (!$main_category)
//     //             return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

//     //         // update date

//     //        $category = array_values($request->categorey) [0];

//     //         // if (!$request->has('categorey.0.active'))
//     //         //     $request->request->add(['active' => 0]);
//     //         // // else
//     //         // //     $request->request->add(['active' => 1]);


//     //         MainCategory::where('id', $mainCat_id)
//     //             ->update([
//     //                 'name' => $category['name'],
//     //                 ///'active' => $request->active,
//     //             ]);

//     //         // save image

//     //         if ($request->has('photo')) {
//     //             $filePath = uploadImage('maincategories', $request->photo);
//     //             MainCategory::where('id', $mainCat_id)
//     //                 ->update([
//     //                     'photo' => $filePath,
//     //                 ]);
//     //         }


//     //         return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
//     //     } catch (\Exception $ex) {
//     //         //return $ex;

//     //         return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
//     //     }

//     // }
//     public function update($id,MainCategoreyRequest $request){
//         //return $request;
//         try {
//             //هنا انا بتاكد ان الاي دي ده موجود فعلا ولا لاء

//             $mainCategory = MainCategory::find($id);
//             if (!$mainCategory) {
//                 return redirect()->route('admin.maincategories')->with(['error' => 'هذه القسم غير موجوده']);
//             }

//              // ديه عشان اجيب اول عنصر ف ال Array
//             $category = array_values($request -> categorey ) [0];

//             // if(!$request->has('category.0.active'))
//             //     $request->request->add(['active' => 0]);
//             // else
//             //      $request->request->add(['active' => 1]);

//             MainCategory::where('id',$id)->update([
//                 'name' => $category ['name'],
//                 'active' => $request -> active,
//             ]);
// //يعني بقوله لو بعتلك صوره اعملها update غير كده لاء
//              if ($request->has('photo')) {
//                 $filePath = uploadImage('maincategories', $request->photo);
//                 MainCategory::where('id', $id)
//                    ->update([
//                         'photo' => $filePath,
//                      ]);
//             }
//             return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح ']);

//         }catch(\Exception $ex){
//             //return $ex;
//             return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);

//         }

//     }

}
