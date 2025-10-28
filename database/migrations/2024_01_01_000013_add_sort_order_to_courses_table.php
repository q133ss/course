<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('thumbnail');
        });

        $courses = DB::table('courses')->orderBy('created_at')->orderBy('id')->get(['id']);

        $position = 1;

        foreach ($courses as $course) {
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['sort_order' => $position++]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
