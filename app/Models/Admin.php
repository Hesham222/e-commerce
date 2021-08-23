<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// ديه مهمه يااتش
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = ['name', 'email','photo','password','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;
}
