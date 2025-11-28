<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_years', function (Blueprint $table) {
            $table->integer('sequence')->nullable()->after('name');
        });

        // Backfill sequence using start_date if available, otherwise parse name or fallback to id order
        $years = DB::table('session_years')->select('id', 'name', 'start_date')->get()->toArray();

        // Sort by start_date if present, else by parsed year, else by id
        usort($years, function ($a, $b) {
            $aDate = $a->start_date ?: null;
            $bDate = $b->start_date ?: null;
            if ($aDate && $bDate) return strcmp($aDate, $bDate);
            if ($aDate) return -1;
            if ($bDate) return 1;

            $ay = extract_base_year($a->name);
            $by = extract_base_year($b->name);
            if ($ay !== null && $by !== null) return $ay <=> $by;
            if ($ay !== null) return -1;
            if ($by !== null) return 1;
            return $a->id <=> $b->id;
        });

        $seq = 1;
        foreach ($years as $y) {
            DB::table('session_years')->where('id', $y->id)->update(['sequence' => $seq++]);
        }
    }

    public function down(): void
    {
        Schema::table('session_years', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
    }
};

// Helper for parsing base year from common patterns
if (!function_exists('extract_base_year')) {
    function extract_base_year(?string $name): ?int {
        if (!$name) return null;
        $name = trim($name);
        if (preg_match('/^(\d{4})\s*[\/-]\s*(\d{4})$/', $name, $m)) {
            $y1 = (int) $m[1];
            $y2 = (int) $m[2];
            return $y2 === $y1 + 1 ? $y1 : null;
        }
        if (preg_match('/^(\d{4})$/', $name, $m)) {
            return (int) $m[1];
        }
        return null;
    }
}

