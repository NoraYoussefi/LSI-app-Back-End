<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Note extends Model
{
    use HasFactory;


    protected $fillable = [
        'valeur_note',
        'mention',
        'module_id',
        'etudiant_id',
    ];

    protected $hidden=[
     
    ];


}
