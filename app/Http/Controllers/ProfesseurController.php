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
            if(auth()->user()['user_type']=='professor'){ //if prof
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
    public function createEventEmploi(Request $request){
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

                    array_push($data,array("id"=>$value['id'],"sujet_pfe"=>$value['sujet_pfe'],"deadline_pfe"=>$value['deadline_pfe'],"commentaire_pfe"=>$value['commentaire_pfe'],"etudiant"=>$etud));

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



    //-----------------UNFINISHED-----------------------GET NOTE OF MODULES------------------------------//
    public function getNote(){
        if(auth()->user()['user_type']=='professor'){
            try{
                $data=[]; $i=0;
                $mod=$this->getModules();
                $notes=[];

                foreach ($mod as $key => $value) {   //loops through modules of prof
                    if(sizeof($note=Note::where('module_id',$value->id)->get())!==0){ //return all notes of module if they exist

// return $etud=Etudiant::find($note[0]->etudiant_id);

                        if($etud=Etudiant::find($note[0]->etudiant_id)){  //finds the student with the note
                            $notesEtud=Note::where('etudiant_id',$etud->id)->where('module_id',$value->id)->get();
                            foreach ($notesEtud as $key => $value) {

                                array_push($data,
                                        array(
                                            "id"=>$note[0]->id,
                                            "valeur_note"=>$note[0]->valeur_note,
                                            "mention"=>$note[0]->mention,
                                            "etudiant"=>$etud->name
                                        )
                                    );
                            }
                        }
                    }
                    //echo array_push($data,Note::where('module_id',$value->id)->get());
                    // if($note=Note::where('module_id',$value->id)->get()[$i++]){
                    //     if($etud=Etudiant::find($note->etudiant_id)->name){
                    //         echo $note;
                    //             array_push($data,
                    //                 array(
                    //                     "id"=>$note->id,
                    //                     "valeur_note"=>$note->valeur_note,
                    //                     "mention"=>$note->mention,
                    //                     "etudiant"=>$etud
                    //                 )
                    //             );
                    //     }


                    // }
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

}
