<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('rsvp_status')->nullable()->after('email');
            $table->unsignedInteger('rsvp_guests_count')->nullable()->after('rsvp_status');
            $table->timestamp('rsvp_responded_at')->nullable()->after('rsvp_guests_count');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['rsvp_status', 'rsvp_guests_count', 'rsvp_responded_at']);
        });
    }
};
