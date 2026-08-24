<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align the table with what the model/controllers actually use:
     * seller_id (not user_id), views_count (not views), plus currency
     * and expires_at which never existed at all.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE marketplace_listings CHANGE user_id seller_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE marketplace_listings CHANGE views views_count INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE marketplace_listings CHANGE description description TEXT NULL');

        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->string('currency', 10)->default('UGX')->after('price');
            $table->timestamp('expires_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->dropColumn(['currency', 'expires_at']);
        });

        DB::statement('ALTER TABLE marketplace_listings CHANGE seller_id user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE marketplace_listings CHANGE views_count views INT NOT NULL DEFAULT 0');
    }
};
