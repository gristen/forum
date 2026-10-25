<?php

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
        Schema::create('bans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Кто забанил (админ). null — если бан выдан системой / без авторизации.
            $table->foreignId('banned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('reason');

            // null — перманентный бан, иначе — дата окончания.
            $table->timestamp('expires_at')->nullable();

            // Заполняется при досрочной разблокировке.
            $table->timestamp('lifted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bans');
    }
};
