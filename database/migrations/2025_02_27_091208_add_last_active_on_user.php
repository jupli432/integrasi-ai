<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_active')->nullable()->after('updated_at');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('last_active')->nullable()->after('updated_at');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->timestamp('last_active')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_active');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('last_active');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('last_active');
        });
    }
};



// ALTER TABLE `users` ADD COLUMN `last_active` TIMESTAMP NULL AFTER `updated_at`;
// ALTER TABLE `companies` ADD COLUMN `last_active` TIMESTAMP NULL AFTER `updated_at`;
// ALTER TABLE `admins` ADD COLUMN `last_active` TIMESTAMP NULL AFTER `updated_at`;