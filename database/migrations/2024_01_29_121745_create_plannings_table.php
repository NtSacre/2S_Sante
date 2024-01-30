<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->enum('jour',['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('status',['disponible','pas_disponible'])->default('disponible');
            $table->boolean('is_deleted')->default(false);
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
