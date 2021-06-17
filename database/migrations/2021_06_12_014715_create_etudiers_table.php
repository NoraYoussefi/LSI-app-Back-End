<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtudiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etudiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');  //foreign key of module
            $table->unsignedBigInteger('etudiant_id');  //foreign key of etudiant

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('etudiant_id')->references('id')->on('etudiants')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etudiers');
    }
}
