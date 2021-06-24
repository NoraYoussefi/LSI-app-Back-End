<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\ProfesseurController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// //  'api/user'   returns the current logged in user
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:api')->get('/index',[AdminController::class, 'index']);


//-------------------------------------AUTHENTIFICATION ROUTES--------------------------------------//
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user-profile', [AuthController::class, 'userProfile']);

});



//------------------------------------------ADMIN CRUD ROUTES---------------------------------------//
Route::group([
    'middleware' => 'api',
    'prefix' => 'admin'

], function () {
    //----------------------AJOUTER UN UTILISATEUR---------------------------//
    Route::post('create-user', [AdminController::class, 'store']);

    //----------------------SUPPRIMMER UN UTILISATEUR------------------------//
    Route::delete('delete-etud/{id_user}', [AdminController::class, 'destroyEtud']);
    Route::delete('delete-prof/{id_user}', [AdminController::class, 'destroyProf']);


    //----------------------SUPPRIMMER UN UTILISATEUR------------------------//
    Route::post('update-etud/{id_user}', [AdminController::class, 'updateEtud']);
    Route::post('update-prof/{id_user}', [AdminController::class, 'updateProf']);
    Route::post('updatepfe/{id}', [AdminController::class, 'updatePFE']);
    Route::post('updatemod/{id}', [AdminController::class, 'updateMOD']);
    Route::post('updatenote/{id}', [AdminController::class, 'updateNOTE']);


    //-------CONSULTER LES PAGES (note,student,prof,emploi,etc)--------------//
    Route::get('consulter', [AdminController::class, 'index']);  //page = (note,etudiant,profs,etc)

    //--------------------AJOUTER MODULES POUR PROFS------------------------//
    Route::post('addmodule/{id_prof}/{NumberOfModules}', [AdminController::class, 'ajouterModules']);


    //-------------------------------------getters---------------------------//
    Route::get('getalletud',[AdminController::class, 'getEtud']);
    Route::get('getallprof',[AdminController::class, 'getProf']);
    //------------------------------------------------------------------------//
    Route::get('getpfes',[AdminController::class, 'getAllPfe']);
    Route::get('getprof/{id}',[AdminController::class, 'getoneprof']);
    Route::get('getetud/{id}',[AdminController::class, 'getoneetud']);

    Route::get('getmodules',[AdminController::class, 'getmodules']);
    Route::get('getnotes',[AdminController::class, 'getNotes']);

    //--------------------SUPRIMMER MODULES POUR PROFS------------------------//
     Route::delete('deletepfe/{id}',[AdminController::class, 'deletePFE']);
     Route::delete('deletemod/{id_module}', [AdminController::class, 'deleteMODULE']);
     Route::delete('deletenote/{id}', [AdminController::class, 'destroyNote']);

});



//-------------------------------------PROFESSEUR CRUD ROUTES-------------------------------------//
Route::group([
    'middleware' => 'api',
    'prefix' => 'professor'

], function () {
    //----------------------AJOUTER NOTE D'ETUDIANT---------------------------//
    Route::post('addnote/{id_etudiant}/{id_module}', [ProfesseurController::class, 'ajouterNote']);

    //----------------------AJOUTER NOTE D'ETUDIANT---------------------------//
    Route::post('addpfe/{id_etudiant}', [ProfesseurController::class, 'ajouterPFE']);

    //----------------------AJOUTER EVENEMENT EMPLOI---------------------------//
    Route::post('addevent', [ProfesseurController::class, 'createEventEmploi']);

    //----------------------GET MODULES OF PROF---------------------------//
    Route::get('getmodules', [ProfesseurController::class, 'getModules']);

    //----------------------GET PFE OF PROF---------------------------//
    Route::get('getpfes', [ProfesseurController::class, 'getPfe']);

    //----------------------GET SUTDENT---------------------------//
    Route::get('getetud', [ProfesseurController::class, 'getStudent']);

    //----------------------GET NOTES---------------------------//
    Route::get('getnote', [ProfesseurController::class, 'getNote']);
});




//-------------------------------------STUDENT READ ROUTES-------------------------------------//
Route::group([
    'middleware' => 'api',
    'prefix' => 'student'

], function () {

    //----------------------GET MODULES OFF ETUDIANT---------------------------//
    Route::get('getmodules', [EtudiantController::class, 'getModules']);

    //----------------------GET PFES OFF ETUDIANT---------------------------//
    Route::get('getpfes', [EtudiantController::class, 'getPfe']);
});

