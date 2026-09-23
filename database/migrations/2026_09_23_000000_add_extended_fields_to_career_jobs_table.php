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
        Schema::table('career_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('career_jobs', 'responsibilities')) {
                $table->longText('responsibilities')->nullable()->after('description');
            }
            if (!Schema::hasColumn('career_jobs', 'benefits')) {
                $table->longText('benefits')->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('career_jobs', 'experience_level')) {
                $table->string('experience_level', 50)->nullable()->after('type');
            }
            if (!Schema::hasColumn('career_jobs', 'workplace_type')) {
                $table->string('workplace_type', 50)->nullable()->default('remote')->after('type');
            }
            if (!Schema::hasColumn('career_jobs', 'salary_period')) {
                $table->string('salary_period', 20)->nullable()->default('month')->after('currency');
            }
            if (!Schema::hasColumn('career_jobs', 'skills')) {
                $table->text('skills')->nullable()->after('salary_period');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'responsibilities',
                'benefits',
                'experience_level',
                'workplace_type',
                'salary_period',
                'skills',
            ]);
        });
    }
};
