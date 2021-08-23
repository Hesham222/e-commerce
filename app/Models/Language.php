<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Language extends Model
{
    use Notifiable;

    protected $table = 'languages';

    protected $fillable = ['id','abbr', 'locale','name','direction','active','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;

    public function scopeActive($query){
        return $query ->where('active',1);
    }
    public function scopeSelection($query){
        return $query ->select('name','abbr','direction','active');
    }

    public function getAtive(){
        return $this ->active == 1 ? ' مفعل'  : 'غير مفعل' ;
    }
}
