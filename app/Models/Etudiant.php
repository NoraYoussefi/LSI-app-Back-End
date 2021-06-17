<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cne',
        'user_id',
    ];

    protected $hidden = [
        'cne',
        'user_id',
    ];



}
