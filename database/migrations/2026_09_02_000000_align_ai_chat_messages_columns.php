<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The API + mobile app store one row per chat message (sender + message),
 * but the original table was built around a single user_message/bot_response
 * pair. That mismatch made every send fail with an "unknown column" MySQL
 * error. This migration reconciles the table with what the app expects.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_chat_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('ai_chat_messages', 'sender')) {
                $table->string('sender', 20)->default('user')->after('user_id');
            }
            if (! Schema::hasColumn('ai_chat_messages', 'message')) {
                $table->text('message')->nullable()->after('sender');
            }
            if (! Schema::hasColumn('ai_chat_messages', 'language')) {
                $table->string('language', 50)->nullable()->after('message');
            }
        });

        // Preserve any legacy content, then relax the old NOT NULL columns so a
        // single-message insert no longer violates the schema. doctrine/dbal is
        // not installed, so use raw MySQL DDL rather than ->change().
        if (Schema::hasColumn('ai_chat_messages', 'user_message')) {
            DB::table('ai_chat_messages')->whereNull('message')->update([
                'message' => DB::raw("COALESCE(NULLIF(user_message, ''), bot_response, '')"),
            ]);

            foreach (['user_message', 'bot_response'] as $column) {
                DB::statement("ALTER TABLE `ai_chat_messages` MODIFY `{$column}` TEXT NULL");
            }
        }
    }

    public function down(): void
    {
        Schema::table('ai_chat_messages', function (Blueprint $table) {
            foreach (['sender', 'message', 'language'] as $column) {
                if (Schema::hasColumn('ai_chat_messages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
