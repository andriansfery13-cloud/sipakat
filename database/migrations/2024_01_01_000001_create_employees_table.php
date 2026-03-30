<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npwp', 20)->unique();
            $table->string('nik', 16)->nullable();
            $table->enum('ptkp_status', ['TK/0', 'K/0', 'K/1', 'K/2', 'K/3'])->default('TK/0');
            $table->string('position')->nullable();
            $table->enum('employee_status', ['tetap', 'tidak_tetap'])->default('tetap');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
