<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_preorders', function (Blueprint $table) {
            if (! Schema::hasColumn('course_preorders', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('contact');
            }

            $table->unique(['course_id', 'ip_address'], 'course_preorders_course_id_ip_address_unique');
        });
    }

    public function down(): void
    {
        Schema::table('course_preorders', function (Blueprint $table) {
            if (Schema::hasColumn('course_preorders', 'ip_address')) {
                $table->dropUnique('course_preorders_course_id_ip_address_unique');
                $table->dropColumn('ip_address');
            }
        });
    }
};
