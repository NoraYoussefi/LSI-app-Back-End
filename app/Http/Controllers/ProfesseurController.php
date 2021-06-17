<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Event;
use App\Models\Module;
use App\Models\Note;
use App\Models\pfe;
use App\Models\Professeur;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ProfesseurController extends Controller
{

    //---------------AJOUTER NOTE POUR CHAQUE ETUDIANT----------------//
    public function ajouterNote(Request $request,$id_student,$id_module){
        try{
            if(auth()->user()['user_type']=='professor'){
                if(Etudiant::find($id_student) && Module::find($id_module)){
                    Note::create([
                        'valeur_note'=>$request['note'],
                        'mention'=>$request['mention'],
                        'module_id'=>$id_module,
                        'etudiant_id'=>$id_student,
                    ]);
                }
                else{
                    return "Student or Module Not Found !!";
                }
            }
        }
        catch(Exception $e){
            return $e;
        }
    }

    //----------------------------------AJOUTER PFE--------------------------------//
    public function ajouterPFE(Request $request,$id_student){
        try{
            $prof_id=Professeur::where('user_id',auth()->user()->id)->get()[0]['id'];

            if(auth()->user()['user_type']=='professor' && $prof_id){

                    if(Etudiant::find($id_student)){
                        pfe::create(
                            [
                                'sujet_pfe'=>$request['sujet_pfe'],
                                'deadline_pfe'=>Carbon::now(),
                                'commentaire_pfe'=>$request['commentaire_pfe'],
                                'id_encadrant'=>$prof_id,
                                'etudiant_id'=>$id_student,
                            ]
                        );
                        return "PFE added !!";
                    }
                    else{
                        return "Student Not Found!!";
                    }
            }
        }
        catch(Exception $e){
            return $e;
        }

    }

    //----------------------------CREATE EVENTS (emploi)-----------------------------//
    public function createEventEmploi(Request $request){
        try{
            if(auth()->user()['user_type']=='professor'){
                $prof_id=Professeur::where('user_id',auth()->user()->id)->get()[0]['id']; //getting the id of prof from prof table

                Event::create(
                        [
                            'event_name'=>$request->event_name,
                            'start_time'=>$request->start_time,
                            'end_time'=>$request->end_time,
                            'id_prof'=>$prof_id,
                        ]
                    );
            }
        }
        catch(Exception $e){
            return $e;
        }
    }


    //----------------------------------------GET EVENT------------------------------//


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Professeur  $professeur
     * @return \Illuminate\Http\Response
     */
    public function show(Professeur $professeur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Professeur  $professeur
     * @return \Illuminate\Http\Response
     */
    public function edit(Professeur $professeur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Professeur  $professeur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Professeur $professeur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Professeur  $professeur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professeur $professeur)
    {
        //
    }



}
