<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restructure sickness_reports so a report is filed against a *type* of
     * animal (with a count) by a user, rather than a single animal record.
     * Only the following domain fields are kept:
     *   affected_animal_type, affected_animal_count, user_id, symptom_primary,
     *   symptom_other, symptom_duration, severity_level, notes, attachments
     * plus the operational columns report_id, status and timestamps.
     */
    public function up(): void
    {
        // Drop the columns that no longer belong to the sickness report.
        $legacy = [
            'animal_id', 'disease_id', 'symptoms', 'reported_by', 'doctor_id',
            'diagnosis', 'treatment', 'medications', 'test_results',
            'reported_date', 'resolved_date',
        ];
        Schema::table('sickness_reports', function (Blueprint $table) use ($legacy) {
            foreach ($legacy as $column) {
                if (Schema::hasColumn('sickness_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Add the new domain columns (guarded so the migration is re-runnable).
        Schema::table('sickness_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('sickness_reports', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('report_id')->index();
            }
            if (!Schema::hasColumn('sickness_reports', 'affected_animal_type')) {
                $table->string('affected_animal_type')->after('user_id');
            }
            if (!Schema::hasColumn('sickness_reports', 'affected_animal_count')) {
                $table->unsignedInteger('affected_animal_count')->default(1)->after('affected_animal_type');
            }
            if (!Schema::hasColumn('sickness_reports', 'symptom_primary')) {
                $table->string('symptom_primary')->after('affected_animal_count');
            }
            if (!Schema::hasColumn('sickness_reports', 'symptom_other')) {
                $table->string('symptom_other')->nullable()->after('symptom_primary');
            }
            if (!Schema::hasColumn('sickness_reports', 'symptom_duration')) {
                $table->string('symptom_duration')->nullable()->after('symptom_other');
            }
            if (!Schema::hasColumn('sickness_reports', 'severity_level')) {
                $table->enum('severity_level', ['mild', 'medium', 'severe', 'critical'])
                    ->default('medium')->after('symptom_duration');
            }
            if (!Schema::hasColumn('sickness_reports', 'attachments')) {
                $table->json('attachments')->nullable()->after('notes');
            }
            // `notes` and `status` already exist from the original migration.
        });
    }

    public function down(): void
    {
        Schema::table('sickness_reports', function (Blueprint $table) {
            foreach ([
                'user_id', 'affected_animal_type', 'affected_animal_count',
                'symptom_primary', 'symptom_other', 'symptom_duration',
                'severity_level', 'attachments',
            ] as $column) {
                if (Schema::hasColumn('sickness_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('sickness_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('animal_id')->nullable();
            $table->unsignedBigInteger('disease_id')->nullable();
            $table->text('symptoms')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('medications')->nullable();
            $table->string('test_results')->nullable();
            $table->timestamp('reported_date')->nullable();
            $table->timestamp('resolved_date')->nullable();
        });
    }
};
