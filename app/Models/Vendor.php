<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;

    protected $table = 'vendors';

    protected $fillable = ['mobile','address','name','email','active','logo','category_id','created_at','updated_at'];
    protected $hidden = ['category_id','created_at','updated_at'];
    public $timestamps = true;


    public function scopeActive($query){
        return $query ->where('active',1);
    }
    public function scopeSelection($query){
        return $query ->select('id','name','category_id','logo','mobile');
    }

    public function getLogoAttribute($val){
        //اول حاجه اتاكدت ان الفاليو فيها صوره بعد كده حطيت ال سيت عشان تجيبلي مسار الصوره من الموقع

        return ($val !==null) ? asset('assets/'.$val) : "";
    }

    public function category(){
        return $this ->belongsTo('App\Models\MainCategory','category_id');
    }

    public function getActive(){
        return $this ->active == 1 ? ' مفعل'  : 'غير مفعل' ;
    }
}
