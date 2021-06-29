<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Event;
use App\Models\Module;
use App\Models\Note;
use App\Models\pfe;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;


class EtudiantController extends Controller
{

    //----------AUTHENTIFICATION OF USER--------------//
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


    public function getModules(){
        try{

            if(Auth::guard()->user()['user_type']=='student'){

                return Module::all();
            }else{
                return "not student";
            }
        }
        catch(Exception $e){
            return $e;
        }
    }


    //-----------------------GET THIS STUDENT PFE-------------//
    public function getPfe(){
        try{
            if(Auth::guard()->user()['user_type']=='student'){

                if($etud_id=Etudiant::where('user_id',auth()->user()->id)->get()[0]['id']){
                    return pfe::where('etudiant_id',$etud_id)->get();
                }
                else{
                    return "Vous N'avez pas de PFE !!";
                }
            }else{
                return "not student";
            }
        }
        catch(Exception $e){
            return $e;
        }
    }


    //--------------GET NOTES OF STUDENT-------------------------//
    public function getNotes(){
        try{
            if(Auth::guard()->user()['user_type']=='student'){
                $data=[];
                if($etud_id=Etudiant::where('user_id',auth()->user()->id)->get()[0]['id']){

                    $note=Note::where('etudiant_id',$etud_id)->get();  //gets the notes of the student

                    foreach ($note as $key => $value) {

                        $module=Module::find($value->module_id);
                        //creates the array
                        array_push($data,
                                array(  "id"=>$value->id,
                                        "note"=>$value->valeur_note,
                                        "mention"=>$value->mention,
                                        "module"=>$module->nom_module
                                    )
                                );

                    }
                    return $data;
                }
            }else{
                return "not student";
            }
        }
        catch(Exception $e){
            return $e;
        }
    }

    //--------------------------------GET EVENTS------------------------------//
    public function getEvents(){
        try{
            if(Auth::guard()->user()['user_type']=='student'){

                return Event::all();

            }else{
                return "not student";
            }
        }
        catch(Exception $e){
            return $e;
        }
    }

}
