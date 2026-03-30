<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xml_logs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('period_month');
            $table->smallInteger('period_year');
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('record_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xml_logs');
    }
};
