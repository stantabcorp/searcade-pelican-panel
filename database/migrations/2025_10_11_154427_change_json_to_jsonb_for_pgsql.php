<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Convert JSON -> JSONB for notifications.data
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE jsonb USING data::jsonb;');

            // Convert JSON -> JSONB for eggs.* columns
            DB::statement('ALTER TABLE eggs ALTER COLUMN features TYPE jsonb USING features::jsonb;');
            DB::statement('ALTER TABLE eggs ALTER COLUMN docker_images TYPE jsonb USING docker_images::jsonb;');
            DB::statement('ALTER TABLE eggs ALTER COLUMN file_denylist TYPE jsonb USING file_denylist::jsonb;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Revert JSONB -> JSON (if needed)
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE json USING data::json;');

            DB::statement('ALTER TABLE eggs ALTER COLUMN features TYPE json USING features::json;');
            DB::statement('ALTER TABLE eggs ALTER COLUMN docker_images TYPE json USING docker_images::json;');
            DB::statement('ALTER TABLE eggs ALTER COLUMN file_denylist TYPE json USING file_denylist::json;');
        }
    }
};
