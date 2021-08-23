<?php

namespace App\Http\Controllers\admin;
use App\Models\Vendor;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\Notification;

class VendorsController extends Controller
{
   public function index(){

      $vendors =   Vendor::selection()->paginate(PAGINATION_COUNT);
      return view('admin.vendors.index',compact('vendors'));

   }
   public function create(){
       $categories = MainCategory::where('translation_of',0)->active()->get();
       return view('admin.vendors.create',compact('categories'));
   }
   public function store(VendorRequest $request){

        try {
            //make validation

            if (!$request->has('active'))
                 $request->request->add(['active' => 0]);
             else
                 $request->request->add(['active' => 1]);
                 $filePath ="";
             if ($request->has('logo')) {
                    $filePath = uploadImage('vendors', $request->logo);

            }


            $vendor = Vendor::create([
                'name' => $request -> name,
                'mobile' => $request -> mobile,
                'email' => $request -> email,
                'active' => $request -> active,
                'address' => $request -> address,
                'logo' =>  $filePath,
                'category_id' =>  $request ->category_id,
            ]);

            //insert to DB

            //redirect message
            Notification::send($vendor, new VendorCreated($vendor) );
            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح ']);

        }catch(\Exception $ex){
            return  $ex;
            //return redirect()->route('admin.vendors')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);
        }


   }


}
