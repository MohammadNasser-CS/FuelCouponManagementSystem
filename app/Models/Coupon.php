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
    public $incrementing = false; // Disable Laravel's default auto-increment behavior

    // protected $fillable = ['id', 'name']; // Define fillable fields

    // Define a mutator to generate the custom 8-digit ID
    public function setIdAttribute($value)
    {
        $this->attributes['id'] = str_pad($value, 8, '0', STR_PAD_LEFT); // Pad with leading zeros
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
