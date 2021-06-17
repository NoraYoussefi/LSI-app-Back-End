<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\Etudiant;
use App\Models\Professeur;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Models\Module;
use App\Models\User;
use Exception;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()   //returns the corresponding page (note,student,prof,emploi,etc)
    {
        // try{
        //     if(auth()->user()['user_type']=='admin'){
            // return $user->getJWTIdentifier();

                return Etudiant::all();
            


                // if($page=='student'){
                //     return Etudiant::all();
                // }
                // if($page=='prof'){
                //     return Professeur::all();
                // }
                /*
                .
                .
                .
                etc
                */
        // }

        // }
        // catch(Exception $e){
        //     return $e;
        // }

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)    //creating a new authenticated user (student or prof) as an admin
    {
        try{
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
                return "fuck off you're not an admin";   //authenticated but not admin !!
            }
        }
        catch(Exception $e){
            return $e;
        }


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)  //update a users info
    {
        try{
            if(auth()->user()['user_type']=='admin'){

                if($user_to_update=User::find($id)){

                    //  //------------UPDATING THE USER IN USERS TABLE-----//
                     $user_to_update->update(['name' => $request->new_name]);
                     $user_to_update->update(['email' => $request->new_email]);
                     $user_to_update->update(['password',bcrypt($request->new_password)]);

                    // //------------UPDATING THE USER IN USERS TABLE-----//
                    // $user_to_update->name=$request['new_name'];
                    // $user_to_update->email=$request['new_email'];
                    // $user_to_update->password=bcrypt($request->new_password);
                    // $user_to_update->push();

                    // // ------------if the user to update is a 'student'----------//
                    if($user_to_update->user_type=="student"){
                        $fk=Etudiant::where('user_id',$id)->get(); //getting the student  REFACTOR LATER !!

                        $update=Etudiant::find($fk[0]['id']);  //getting the ID

                        $update->update(['name' => $request->new_name]);
                        $update->update(['email' => $request->new_email]);

                    }
                    // //-----------if the user to update is a 'professor'-----------//
                    else if($user_to_update->user_type=="professor"){
                        $fk=Professeur::where('user_id',$id)->get();  //getting the student  REFACTOR LATER !!

                        $update=Professeur::find($fk[0]['id']);  //getting the ID

                        $update->update(['name' => $request->new_name]);
                        $update->update(['email' => $request->new_email]);

                    }
                }
                else{
                    return "The user doesn't exist";
                }
            }
        }
        catch(Exception $e){
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)   //deletes the user
    {
        try{

            if(auth()->user()['user_type']=='admin'){   //only if authenticated and admin
                                                        //token must be stored !!
                if(User::find($id)){
                    User::find($id)->delete();
                    return "User Successfully Deleted";
                }else{
                    return "User Not Found !!";
                }
            }
            else{
                return "unauthorized";
            }
        }
        catch(Exception $e){
            return $e;
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
        try{
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
                return "unauthorized";
            }
        }
        catch(Exception $e){
            return $e;
        }
    }


    //---------------------------------SUPPRIMMER MODULES--------------------------//
    public function deleteModule($idModule){
        try{
            if(auth()->user()['user_type']=='admin'){

                if(Module::find($idModule)->delete()){
                    return "Successfully Deleted";
                }
                else{
                    return "Module Not found !!";
                }
            }
        }
        catch(Exception $e){
            return $e;
        }

    }


}
