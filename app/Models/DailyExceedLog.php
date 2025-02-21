<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyExceedLog extends Model
{
    protected $fillable = ['user_id', 'trip_id', 'logged_date'];
}
