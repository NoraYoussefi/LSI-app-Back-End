<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\Etudiant;
use App\Models\Professeur;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\pfe;
use App\Http\Controllers\AuthController;
use App\Models\Module;
use App\Models\Note;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\countOf;

class AdminController extends Controller
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEtud()   //returns the corresponding page (note,student,prof,emploi,etc)
    {
       if(Auth::guard()->user()['user_type']=='admin'){
            return Etudiant::all();
        }else{
            return "not admin";
        }
    }

    public function getProf()   //returns the corresponding page (note,student,prof,emploi,etc)
    {
       if(Auth::guard()->user()['user_type']=='admin'){
            return Professeur::all();
        }else{
            return "not admin";
        }
    }
    //------------------------------------------------------------------//
    public function getAllPfe(){
        if(Auth::guard()->user()['user_type']=='admin'){
            $pfes=pfe::all();

            $data=[];

            foreach ($pfes as $key => $value){
                $enc=Professeur::find($value->id_encadrant)['name'];
                $etud=Etudiant::find($value->etudiant_id)['name'];

                array_push($data,array("id"=>$value['id'],"sujet_pfe"=>$value['sujet_pfe'],"deadline_pfe"=>$value['deadline_pfe'],"commentaire_pfe"=>$value['commentaire_pfe'],"encadrant"=>$enc,"etudiant"=>$etud));

            };
            return $data;
        }else{
            return "not admin";
        }
    }

    //------------------------------------------------------------------//
    public function getNotes(){
        if(Auth::guard()->user()['user_type']=='admin'){
            $notes=Note::all();

            $data=[];

            foreach ($notes as $key => $value){
                $module=Module::find($value->module_id)['nom_module'];
                $etud=Etudiant::find($value->etudiant_id)['name'];

                array_push($data,array("id"=>$value['id'],"note"=>$value['valeur_note'],"mention"=>$value['mention'],"module"=>$module,"etudiant"=>$etud));

            };
            return $data;
        }else{
            return "not admin";
        }
    }

    //-------------------------------------------------------------------//
    public function getmodules(){
        if(Auth::guard()->user()['user_type']=='admin'){
            $modules=Module::all();

            $data=[];

            foreach ($modules as $key => $value){
                $prof=Professeur::find($value->id_prof)['name'];

                array_push($data,array("id"=>$value['id'],"nom_module"=>$value['nom_module'],"prof"=>$prof));
            };
            return $data;
        }else{
            return "not admin";
        }
    }


    public function updatePFE(Request $request,$id_pfe){

        if(Auth::guard()->user()['user_type']=='admin'){
            if($pfe_update=pfe::find($id_pfe)){

                $pfe_update->update(['sujet_pfe' => $request->sujet_pfe]);
                $pfe_update->update(['deadline_pfe' => $request->deadline_pfe]);
                $pfe_update->update(['commentaire_pfe' => $request->commentaire_pfe]);

                return "PFE successfully updated";
            }
            else{
                return "PFE not found !!";
            }
        }

    }

    //----------------------------UPDATE NOTE---------------------------------------------//
    public function updateNOTE(Request $request,$id){

        if(Auth::guard()->user()['user_type']=='admin'){
            if($note=Note::find($id)){

                $note->update(['valeur_note' => $request->note]);
                $note->update(['mention' => $request->mention]);

                return "NOTE successfully updated";
            }
            else{
                return "PFE not found !!";
            }
        }

    }

    public function deletePFE($id){
        if(Auth::guard()->user()['user_type']=='admin'){
            if(pfe::find($id)->delete()){
                return "Successfully Deleted!!";
            }
        }
        else{
            return "PFE Not found !!";
        }

    }

    //---------------------------------SUPPRIMMER MODULES--------------------------//
    public function deleteMODULE($id){
        if(Auth::guard()->user()['user_type']=='admin'){
            if(Module::find($id)->delete()){
                return "Successfully Deleted!!";
            }
        }
        else{
            return "Module Not found !!";
        }
    }



    //----------------------------------AJOUTER PFE--------------------------------//
    public function ajouterPFE(Request $request,$id_prof,$id_student){

        $prof_id=Professeur::where('user_id',auth()->user()->id)->get()[0]['id'];

            if(auth()->user()['user_type']=='admin' && $prof_id){

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
            else{
                "Not professor !!";
            }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)    //creating a new authenticated user (student or prof) as an admin
    {
            if(auth()->user()['user_type']=='admin'){
                //don't forget 'password_confirmation' in the front-end form
                $new_user=app('App\Http\Controllers\AuthController')->register($request);  //registers a user in users table, to use token

                $id=(collect($new_user)->toArray())['original']['user']['id'];

                  //the user_id (fk) of the new user (prof/student)

                if($request['user_type']=='student'){
                    Etudiant::create([
                        'name'=>$request['name'],
                        'email'=>$request['email'],
                        'user_id'=>$id,
                        'cne'=>$id,   //can be changed to actual CNE in admin CRUD
                    ]);
                }
                else if($request['user_type']=='professor'){
                    Professeur::create([
                        'name'=>$request['name'],
                        'email'=>$request['email'],
                        'user_id'=>$id,
                        'js_id_emploi'=>'calendar_id_'.$id,  //creating the id of the calendar = user_id (fix it!!)
                    ]);
                }
                else{
                    return "Invalid User Type :(, Must be 'student' or 'professor' !!!";
                }

                return $new_user;  //return the created user
            }
            else{
                return "not  admin";   //authenticated but not admin !!
            }

    }

    public function updateProf(Request $request, $id){

        if(auth()->user()['user_type']=='admin'){

            if($fk=Professeur::find($id)['user_id']){

                //  //------------UPDATING THE USER IN USERS TABLE-----//
                User::find($fk)->update(['name' => $request->new_name]);
                User::find($fk)->update(['email' => $request->new_email]);
                 //--------------------------------------------------------//
                 Professeur::find($id)->update(['name' => $request->new_name]);
                 Professeur::find($id)->update(['email' => $request->new_email]);

                return "Successfully updated!!";
            }
            else{
                return "The user doesn't exist";
            }
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function updateEtud(Request $request, $id)  //update a users info
    {
            if(auth()->user()['user_type']=='admin'){

                if($fk=Etudiant::find($id)['user_id']){

                    //  //------------UPDATING THE USER IN USERS TABLE-----//
                    User::find($fk)->update(['name' => $request->new_name]);
                    User::find($fk)->update(['email' => $request->new_email]);
                     //--------------------------------------------------------//
                     Etudiant::find($id)->update(['name' => $request->new_name]);
                     Etudiant::find($id)->update(['email' => $request->new_email]);

                    return "Successfully updated!!";
                }
                else{
                    return "The user doesn't exist";
                }
            }
    }


    public function updateMOD(Request $request, $id)  //update a users info
    {
            if(auth()->user()['user_type']=='admin'){
                if($mod=Module::find($id)){
                    $mod->update(['nom_module'=>$request->nom_module]);
                }
                return "Successfully updated!!";
                }
                else{
                    return "Module doesn't exist";
                }
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */

    public function destroyEtud($id){  //deletes the user
            if(auth()->user()['user_type']=='admin'){   //only if authenticated and admin
                                                        //token must be stored !!
                if($fk=Etudiant::find($id)['user_id']){
                    User::find($fk)->delete();
                    return "User Successfully Deleted";
                }
                else{
                    return "User Not Found !!";
                }
            }

            else{
                return "not admin";
            }

    }

    public function destroyProf($id)   //deletes the user
    {
            if(auth()->user()['user_type']=='admin'){   //only if authenticated and admin
                                                        //token must be stored !!
                if($fk=Professeur::find($id)['user_id']){
                    User::find($fk)->delete();
                    return "User Successfully Deleted";
                }
                else{
                    return "User Not Found !!";
                }
            }

            else{
                return "not admin";
            }
    }

    //---------------------------------------delete note-------------------------------//
    public function destroyNote($id)   //deletes the user
    {
            if(auth()->user()['user_type']=='admin'){   //only if authenticated and admin
                                                        //token must be stored !!
                if(Note::find($id)->delete()){
                    return "Note Successfully Deleted";
                }
                else{
                    return "User Not Found !!";
                }
            }
            else{
                return "not admin";
            }

    }



    //-------------------CREATING MODULES FOR PROFS-------------------------------//
    public function ajouterModules(Request $request, $id,$nbreModule){  //id of prof & nbreDeModules a ajouter
        /*
        FORM:
        pick number of modules to add to a selected prof
        each input will have a name 'module_1','module_2' , etc
        */
        $i=1;
        if(auth()->user()['user_type']=='admin'){

            if(Professeur::find($id)){
                while ($i <= $nbreModule) {
                    Module::create([
                        'nom_module'=>$request['module_'.$i++],
                        'id_prof'=>$id,  //ID du prof choisit
                    ]);
                }
            }
            else{
                return "this ID doesn't exist !!";
            }
        }
        else{
            return "not admin";
        }
    }




}
