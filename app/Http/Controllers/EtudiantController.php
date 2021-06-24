<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Module;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function show(Etudiant $etudiant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function edit(Etudiant $etudiant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Etudiant $etudiant)
    {
        //
    }
}
