<?php

use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\TemoignageController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HopitalController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SecteurActiviteController;
use App\Http\Controllers\VilleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('register-medecin','registerMedecin');
    Route::post('register-patient','registerPatient');





});

Route::middleware('medecin')->group(function(){
    Route::post('/article', [ArticleController::class, 'store'])->name('article.store');
    Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
    Route::post('/update-article/{article}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('/article/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');
    Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show');
// route pour les planning meddecin
    Route::post('/planning', [PlanningController::class, 'store'])->name('planning.store');
    Route::post('/update-planning/{planning}', [PlanningController::class, 'update'])->name('planning.update');
    Route::delete('/planning/{planning}', [PlanningController::class, 'destroy'])->name('planning.destroy');
    Route::get('/planning/{planning}', [PlanningController::class, 'show'])->name('planning.show');
    Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');
// route pour la consultation medecin
    Route::get('/liste-consultation', [ConsultationController::class, 'index'])->name('consultation.index');
    Route::get('/accepter-consultation/{consultation}',
    [ConsultationController::class, 'accepterConsultation'])
    ->name('consultation.accepterConsultation');


    Route::get('/effectuer-consultation/{consultation}',
    [ConsultationController::class, 'effectuerConsultation'])
    ->name('consultation.effecuterConsultation');

    Route::get('/refuser-consultation/{consultation}',
    [ConsultationController::class, 'refusConsultation'])
    ->name('consultation.refuserConsultation');
    Route::get('/contacter-patient/{patient}', [ConsultationController::class, 'contacterPatient'])
    ->name('contacterPatient');
    Route::post('/modifier-compte/medecin/{medecin}',
    [AuthController::class, 'modificationMedecin']);
});

Route::middleware('patient')->group(function () {
    Route::post('/consulter-docteur', [ConsultationController::class, 'store'])->name('consultation.store');
    Route::get('/detail-planning/{planning}', [HomeController::class, 'DetailPlanning'])->name('home.detail-planning');
    Route::get('/mes-consultations', [ConsultationController::class, 'listeConsultationPatient'])
    ->name('consultation.patient');

    Route::post('/modifier-compte/patient/{patient}',[AuthController::class, 'modificationPatient']);
    Route::apiResource('/temoignage', TemoignageController::class);

});

Route::middleware('admin')->group(function () {
    Route::get('debloquer-user/{user}', [AuthController::class,'debloquerUser' ])->name('admin.debloquerUser');
    Route::get('bloquer-user/{user}', [AuthController::class,'bloquerUser' ]);
    Route::post('valider-compte-medecin/{medecin}', [AuthController::class, 'accepterMedecin']);
    Route::apiResource('/role', RoleController::class);
    Route::apiResource('/hopital', HopitalController::class);
    Route::apiResource('/secteur-activite', SecteurActiviteController::class);
    Route::apiResource('/ville', VilleController::class);



    Route::get('liste-medecin/', [AuthController::class, 'listeMedecin']);
    Route::get('liste-user/', [AuthController::class, 'allUser']);

    Route::get('liste-patient/', [AuthController::class, 'listePatient']);



});

Route::prefix('/home')->name('home.')->group(function(){
    Route::get('/', [HomeController::class, 'article'])->name('article');
    Route::get('/voir-article/{article}', [HomeController::class, 'voirArticle'])->name('voirArticle');
    Route::get('/planning-medecin/{medecin}', [HomeController::class, 'planningMedecin'])->name('planningMedecin');
    Route::get('detail-medecin', [HomeController::class, 'detailMedecin'])->name('detailMedecin');
    Route::get('/user-temoignages', [HomeController::class, 'toutTemoignage'])->name('toutTemoignage');



  });

  Route::post('motpasseoublie', [ResetPasswordController::class, 'soumettreMotpassOublie'])
    ->name('motpasse.oublie.post');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])
    ->name('reset.password.get');
Route::post('reset-password', [ResetPasswordController::class, 'submitResetPasswordForm'])
    ->name('reset.password.post');


