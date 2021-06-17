<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

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
    Route::get('/index',[AdminController::class, 'index']);

});



//------------------------------------------ADMIN CRUD ROUTES---------------------------------------//
Route::group([
    'middleware' => 'api',
    'prefix' => 'admin'

], function () {
    //----------------------AJOUTER UN UTILISATEUR---------------------------//
    Route::post('create-user', [AdminController::class, 'store']);

    //----------------------SUPPRIMMER UN UTILISATEUR------------------------//
    Route::delete('delete-user/{id_user}', [AdminController::class, 'destroy']);

    //----------------------SUPPRIMMER UN UTILISATEUR------------------------//
    Route::post('update-user/{id_user}', [AdminController::class, 'update']);

    //-------CONSULTER LES PAGES (note,student,prof,emploi,etc)--------------//
    Route::get('consulter', [AdminController::class, 'index']);  //page = (note,etudiant,profs,etc)

    //--------------------AJOUTER MODULES POUR PROFS------------------------//
    Route::post('addmodule/{id_prof}/{NumberOfModules}', [AdminController::class, 'ajouterModules']);

     //--------------------SUPRIMMER MODULES POUR PROFS------------------------//
     Route::delete('delete-module/{id_module}', [AdminController::class, 'deleteModule']);

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
});

