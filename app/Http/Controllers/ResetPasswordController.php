<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    /**
     * Show the forget password form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showForgetPasswordForm()
    {
        return response()->json(['message' => 'Afficher le formulaire d\'oublié de mot de passe']);
    }

    /**
     * Submit the forget password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function soumettreMotpassOublie(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        
        // Vérifier si un jeton existe déjà pour cette adresse e-mail
        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();
        
        if ($existingToken) {
            $token = Str::random(64);
        
            // Mettre à jour le jeton existant
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->update([
                    'token' => $token,
                    'created_at' => now(),
                ]);
        } else {
            // Si aucun jeton n'existe, générer un nouveau jeton
            $token = Str::random(64);
        
            // Insérer le nouveau jeton dans la table
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);
        }
        
        // Envoyer l'e-mail avec le jeton
        Mail::send('email.MailConfirmeReset', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        
        return response()->json(['message' => 'Email de récupération envoyé avec succès!']);
        
    }

    /**
     * Show the reset password form.
     *
     * @param  string  $token
 
     */
    public function showResetPasswordForm($token)
    {
        return view('resetPassword', ['token' => $token]);
    }

    /**
     * Submit the reset password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitResetPasswordForm(Request $request)
    {
  
   $request->validate([
    'password' => 'required|string|min:8|confirmed',
    'password_confirmation' => 'required|same:password',
]);
    dd($request->token);
        $updatePassword = DB::table('password_reset_tokens')
            ->where([

                'token' => $request->token,
            ])
            ->first();

dd($updatePassword);
        if (!$updatePassword) {
           
            return response()->json(['error' => 'Ressouces introuvables'], 404);
        }
        $user = DB::table('password_reset_tokens')->where(['token' => $request->token])->first();
       $user= User::where('email', $user->email)
            ->update(['password' => Hash::make($request->password)]);

            

        DB::table('password_reset_tokens')->where(['token' => $request->token])->delete();

        return response()->json(['message' => 'Votre mot de passe est mis a jour avec succès']);
    }
}
