<?php

use App\Models\Role;
use App\Models\Ville;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telephone')->unique();
            $table->boolean('is_blocked')->default(false);
            $table->enum('genre',['homme','femme']);

            $table->foreignIdFor(Ville::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Role::class)->constrained()->onDelete('cascade');

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
