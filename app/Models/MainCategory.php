<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MainCategory extends Model
{
    use Notifiable;

    protected $table = 'main_categories';

    protected $fillable = ['translation_lang','translation_of','name','slug','active','photo','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;



    public function scopeActive($query){
        return $query ->where('active',1);
    }

    public function scopeSelection($query){
        return $query ->select('id','translation_lang','name','slug','photo','active','translation_of');
    }

    public function getPhotoAttribute($val){
        //اول حاجه اتاكدت ان الفاليو فيها صوره بعد كده حطيت ال سيت عشان تجيبلي مسار الصوره من الموقع

        return ($val !==null) ? asset('assets/'.$val) : "";
    }

    public function getActive(){
        return $this ->active == 1 ? ' مفعل'  : 'غير مفعل' ;
    }
    public function categories(){
        return $this-> hasMany(self::class,'translation_of');
    }

    //الكاتجورى بتحتووي ع متاجر كتير لكن كل تاجر بينتمي لكاتجورى واحد
    public function vendors(){
        return $this ->hasMany('App\Models\Vendor','category_id');
    }


}
