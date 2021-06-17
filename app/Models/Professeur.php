<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'email',
        'js_id_emploi',
        'user_id',
    ];


    protected $hidden = [
        'js_id_emploi',
        'user_id',
    ];


}
