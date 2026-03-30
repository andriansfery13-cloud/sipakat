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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin_kecamatan')->after('id');
            $table->foreignId('kecamatan_id')->nullable()->constrained()->nullOnDelete()->after('role');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });

        Schema::table('xml_logs', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn(['role', 'kecamatan_id']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn('kecamatan_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn('kecamatan_id');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn('kecamatan_id');
        });

        Schema::table('xml_logs', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn('kecamatan_id');
        });
    }
};
