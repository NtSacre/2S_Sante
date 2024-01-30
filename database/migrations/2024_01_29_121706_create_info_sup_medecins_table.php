<?php

use App\Models\Hopital;
use App\Models\SecteurActivite;
use App\Models\User;
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
        Schema::create('info_sup_medecins', function (Blueprint $table) {
            $table->id();
            $table->boolean('accepter')->default(false);
            $table->string('image')->nullable();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Hopital::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(SecteurActivite::class)->constrained()->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_sup_medecins');
    }
};
