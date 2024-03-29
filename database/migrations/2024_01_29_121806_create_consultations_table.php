<?php

use App\Models\User;
use App\Models\Planning;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->date('date');

            $table->time('heure');

            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Planning::class)->constrained()->onDelete('cascade');
            $table->enum('etat',[ 'effectuer', 'non_effectuer'])->default('non_effectuer');
            $table->enum('motif',['Consultation_generale',
            'Prescription_de_médicaments_renouvelables',
            'Suivi_de_traitement',
            'Conseils_sur_des_symptomes_mineurs',
            'Medecine_preventive','Problemes_de_sante_mentale',
            'Deuxieme_avis_medical','Suivi_post_operatoire',
            'Question_de_sante_sexuelle']);
            $table->enum('status',['accepter','refuser','en_attente',])->default('en_attente');
            $table->enum('type',['en_ligne','presentiel']);
            $table->longText('prescription')->nullable();

            $table->timestamp('rappel_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
