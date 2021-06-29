<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Event;
use App\Models\Module;
use App\Models\Note;
use App\Models\pfe;
use App\Models\Professeur;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class ProfesseurController extends Controller
{

    // //----------AUTHENTIFICATION OF USER--------------//
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    //end __construct()
    protected function guard(){
        return Auth::guard();
    }
    // //---------------END OF AUTHENTIFICATION--------------//




    //---------------AJOUTER NOTE POUR CHAQUE ETUDIANT----------------//
    public function ajouterNote(Request $request,$id_student,$id_module){
        
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
            else{
                "Not professor !!";
            }
    }



    //----------------------------CREATE EVENTS (emploi)-----------------------------//
    public function createEvent(Request $request){
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
            else{
                "Not professor !!";
            }
    }

    //-----------------------------GET PROF EVENTS------------------------//
    public function getEvent(){
        if(auth()->user()['user_type']=='professor'){
            $prof_id=Professeur::where('user_id',auth()->user()->id)->get()[0]['id']; //getting the id of prof from prof table

            return Event::where('id_prof',$prof_id)->get();
        }
        else{
            "Not professor !!";
        }
    }


    //----------------------------------------GET MODULES------------------------------//

    public function getModules(){
        if(auth()->user()['user_type']=='professor'){
            try{

                $id=Professeur::where('user_id',auth()->user()->id)->get()[0]['id'];
                return Module::where('id_prof',$id)->get();
            }
            catch(Exception $e){
                return $e;
            }
        }
        else{
            "Not professor !!";
        }
    }


    //----------------------------------------GET PFES------------------------------//

    public function getPfe(){
        if(auth()->user()['user_type']=='professor'){
            try{

                $id=Professeur::where('user_id',auth()->user()->id)->get()[0]['id'];
                $pfes=pfe::where('id_encadrant',$id)->get();
                $data=[];
                foreach ($pfes as $key => $value){
                    $etud=Etudiant::find($value->etudiant_id)['name'];

                    array_push($data,
                        array(  "id"=>$value['id'],
                                "sujet_pfe"=>$value['sujet_pfe'],
                                "deadline_pfe"=>$value['deadline_pfe'],
                                "commentaire_pfe"=>$value['commentaire_pfe'],
                                "etudiant"=>$etud
                            )
                        );

                };
                return $data;
            }
            catch(Exception $e){
                return $e;
            }
        }
        else{
            "Not professor !!";
        }
    }




    //----------------------------------------GET NOTE OF MODULES------------------------------//
    public function getNote(){
        if(auth()->user()['user_type']=='professor'){
            try{
                $data=[];
                $mod=$this->getModules();
                $notes=[];
                $i=0;
                foreach ($mod as $key => $value) {   //loops through modules of prof
                    if(sizeof($note=Note::where('module_id',$value->id)->get())!==0){ //return all notes of module if they exist
                            $notesEtud=Note::where('module_id',$value->id)->get();
                            foreach ($notesEtud as $key => $valueNote) {
                                $etud=Etudiant::find($valueNote->etudiant_id);

                                array_push($data,
                                        array(
                                            "id"=>$valueNote->id,
                                            "etudiant"=>$etud->name,
                                            "valeur_note"=>$valueNote->valeur_note,
                                            "module"=>$value['nom_module'],
                                            "mention"=>$valueNote->mention,
                                        )
                                    );
                            }
                    }
                }
               return $data;
            }
            catch(Exception $e){
                return $e;
            }
        }
        else{
            "Not professor !!";
        }
    }

//--------------------------------------------------------------------------------------------------------------//


public function deleteNote($id){
    if(auth()->user()['user_type']=='professor'){
        try{
            Note::find($id)->delete();
        }
        catch(Exception $e){
            return $e;
        }
    }
    else{
        "Not professor !!";
    }
}



}
