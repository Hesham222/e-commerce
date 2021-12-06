<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Vendor;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\Notification;

class VendorsController extends Controller
{


    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);
 
            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'logo' => $filePath,
                'password' => $request->password,
                'category_id' => $request->category_id,
                //الاحداثيات بتاعت جوجل ماب
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,

            ]);

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            //return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

    public function edit($id)
    {
        try {

            $vendors = Vendor::Selection()->find($id);
            if (!$vendors)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);

            $categories = MainCategory::where('translation_of', 0)->active()->get();

            return view('admin.vendors.edit', compact('vendors', 'categories'));

        } catch (\Exception $ex) {
            //return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
    public function update($id,VendorRequest $request){

        try{
            $vendors = Vendor::Selection()->find($id);
            if (!$vendors)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
            DB::beginTransaction();
            //photo
                if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->photo);
                Vendor::where('id', $id)
                    ->update([
                        'logo' => $filePath,
                    ]);
            }

            $data = $request->except('_token','id','password','logo');
            //password
            if($request ->has('password') && !is_null($request->  password) ){
                $data['password'] = $request -> password;
            }
            Vendor::where('id', $id)
            ->update($data);
            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم ألتحديث بنجاح']);
        }catch(\Exception $ex){
           // return $ex;
            DB::rollback();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function destroy($id){
        try{
            $delete_vendor = Vendor::find($id);
            if (!$delete_vendor) {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود ']);
            }

            // مبتمسحش الا من اول  فولدر الصور وكل الصور عندي متخزنه بالمسار من اوله ف بستخدم ال س ت ر عشان اقوله يقطع من اول الاسيت ل  unlink
            $image =  Str::after($delete_vendor -> logo,'assets/');

            $img = base_path('assets/'.$image);// بتجيب مسار الصوره ع اللابتوب عندي
            unlink($img);


            $delete_vendor ->delete();
            return redirect()->route('admin.vendors')->with(['success' => 'تم الحذف بنجاح ']);
    }catch(\Exception $ex){
        //return $ex;
        return redirect()->route('admin.vendors')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);
    }
    }

    public function changeStatus($id){
        try{

            $vendor = Vendor::find($id);

            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا القسم غير موجود ']);

            $status = $vendor -> active == 0 ? 1 :0;
            $vendor ->update(['active' => $status]);

            return redirect()->route('admin.vendors')->with(['success' => 'تم تحديث الحاله بنجاح ']);

        }catch(\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);
        }
    }




}
