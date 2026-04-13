<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogretmen_id');
            $table->unsignedBigInteger('ders_id');
            $table->string('token', 120)->unique();
            $table->date('tarih');
            $table->boolean('aktif')->default(true);
            $table->timestamp('kapanis_zamani')->nullable();
            $table->timestamps();

            $table->index(['ders_id', 'tarih']);
            $table->index(['ogretmen_id', 'aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
