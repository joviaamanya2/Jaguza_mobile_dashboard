<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The DB enum only allowed healthy/sick/injured/recovering/pregnant/
     * lactating/critical, but every controller (Admin\AdminAnimalController,
     * Api\AnimalController) and the mobile app validate/offer
     * healthy/sick/treatment/quarantine/recovering/critical instead - so
     * saving "Under Treatment" or "Quarantine" always failed with a DB
     * truncation error. Union both sets (dropping pregnant/lactating, which
     * nothing selects) so existing data and the app-level values both work.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE animals MODIFY health_status ENUM('healthy','sick','injured','treatment','quarantine','recovering','critical') NOT NULL DEFAULT 'healthy'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE animals MODIFY health_status ENUM('healthy','sick','injured','recovering','pregnant','lactating','critical') NOT NULL DEFAULT 'healthy'");
    }
};
