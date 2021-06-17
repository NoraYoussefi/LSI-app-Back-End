<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pves', function (Blueprint $table) {
            $table->id();
            $table->string('sujet_pfe')->unique();
            $table->timestamp("deadline_pfe");
            $table->string('commentaire_pfe')->default("commentaire a ajouter");

            //prof foreign key
            $table->unsignedBigInteger('id_encadrant');
            $table->foreign('id_encadrant')->references('id')->on('professeurs')->onUpdate('cascade')->onDelete('cascade');

            //etudiant foreign key
            $table->unsignedBigInteger('etudiant_id');
            $table->foreign('etudiant_id')->references('id')->on('etudiants')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('pves');
    }
}
