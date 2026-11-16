<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VolunteerApplication extends Model { protected $fillable=['user_id','phone','skills','availability','experience','status','reviewed_by']; public function user(){return $this->belongsTo(User::class);} }
