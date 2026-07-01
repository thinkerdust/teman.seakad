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
        // 1. Update invitations table
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('bride_nickname')->nullable()->after('bride_name');
            $table->string('groom_nickname')->nullable()->after('groom_name');
            $table->string('bride_photo')->nullable()->after('bride_nickname');
            $table->string('groom_photo')->nullable()->after('groom_nickname');
            $table->json('customization')->nullable()->after('description');
        });

        // 2. Update stories table
        Schema::table('stories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
        });

        // 3. Update galleries table
        Schema::table('galleries', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['bride_nickname', 'groom_nickname', 'bride_photo', 'groom_photo', 'customization']);
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('is_visible');
        });
    }
};
