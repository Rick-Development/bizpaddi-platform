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
        try {
            $categories = DB::table('service_categories')->whereNotNull('image')->get();
            foreach ($categories as $cat) {
                $raw = $cat->image;
                if (!$raw) continue;

                $clean = $raw;
                $clean = preg_replace('/^https?:\/\/[^\/]+/', '', $clean);
                while (preg_match('/^\/?storage\//i', $clean)) {
                    $clean = preg_replace('/^\/?storage\//i', '', $clean);
                }
                if (preg_match('/https?:\/\/[^\/]+\/(.*)$/i', $clean, $matches)) {
                    $clean = $matches[1];
                    while (preg_match('/^\/?storage\//i', $clean)) {
                        $clean = preg_replace('/^\/?storage\//i', '', $clean);
                    }
                }
                $clean = ltrim($clean, '/');

                DB::table('service_categories')->where('id', $cat->id)->update([
                    'image' => $clean
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore if table doesn't exist yet
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
