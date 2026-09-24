<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('theme_color', 'primary_color');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('secondary_color')->nullable()->after('primary_color');
            $table->string('font_color_primary')->nullable()->after('secondary_color');
            $table->string('font_color_secondary')->nullable()->after('font_color_primary');
            $table->string('font_family')->nullable()->after('font_color_secondary');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['secondary_color', 'font_color_primary', 'font_color_secondary', 'font_family']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('primary_color', 'theme_color');
        });
    }
};
