<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    // protected $table = 'student_course';
    // protected $primaryKey = ['student_id', 'course_id'];
    // public $incrementing = false;
    // protected $casts = [

    // ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
