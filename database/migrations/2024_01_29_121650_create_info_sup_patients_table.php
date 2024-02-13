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
        Schema::create('info_sup_patients', function (Blueprint $table) {
            $table->id();
            $table->integer('age');
            $table->integer('poids');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_sup_patients');
    }
};
