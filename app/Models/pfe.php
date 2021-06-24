<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pfe extends Model
{
    use HasFactory;

    protected $fillable=[
        'sujet_pfe',
        'deadline_pfe',
        'commentaire_pfe',
        'etudiant_id',
        'id_encadrant'
    ];

    public function etudiant(){
        
    }

    // protected $hidden=[
    //     'etudiant_id',
    //     'id_encadrant'
    // ];
}
