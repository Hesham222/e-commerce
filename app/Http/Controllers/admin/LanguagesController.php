<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;

class LanguagesController extends Controller
{
    public function index(){

        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));
    }
    public function create(){
        return view('admin.languages.create');
    }
    public function store(LanguageRequest $request){
        try{
            if (!$request->has('active'))
            $request->request->add(['active' => 0]); 
        else
            $request->request->add(['active' => 1]);

            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم الحفظ بنجاح ']);
        }catch(\Exeption $ex){
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);

        }
    }

    public function edit($id){
        $language = Language::select()->find($id);
        if(!$language){
            return redirect()->route('admin.languages')->with(['error' => 'هذه اللغه غير موجوده']);
        }
        return view('admin.languages.edit',compact('language'));
    }

    public function update($id,LanguageRequest $request){
        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هذه اللغه غير موجوده']);
            }
            if(!$request->has('active'))
                $request -> request ->add(['active' => 0]);
            else
                 $request -> request ->add(['active' =>1]);
            $language -> update($request ->except('_token'));

            return redirect()->route('admin.languages')->with(['success' => 'تم الحفظ بنجاح ']);

        }catch(\Exeption $ex){
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);

        }
    }

    public function destroy($id){
        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages')->with(['error' => 'هذه اللغه غير موجوده']);
            }
            $language -> delete();

            return redirect()->route('admin.languages')->with(['success' => 'تم الحذف بنجاح ']);

        }catch(\Exeption $ex){
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ ما يرجي المحاوله فيما بعد ']);

        }
    }

}
