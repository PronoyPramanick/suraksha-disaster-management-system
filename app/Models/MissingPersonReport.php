<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MissingPersonReport extends Model
{
    protected $fillable = ['user_id', 'report_type', 'name', 'age', 'last_known_location', 'last_seen_date', 'photo_path', 'contact_method', 'details', 'status'];
}
