<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->integer('term_order')->nullable()->after('name');
        });

        // Backfill term_order based on common naming or existing order
        $terms = DB::table('terms')->select('id', 'name')->get();

        foreach ($terms as $term) {
            $order = null;
            $name = strtolower(trim($term->name ?? ''));
            if (preg_match('/1st|first|\b1\b/', $name)) {
                $order = 1;
            } elseif (preg_match('/2nd|second|\b2\b/', $name)) {
                $order = 2;
            } elseif (preg_match('/3rd|third|\b3\b/', $name)) {
                $order = 3;
            }

            DB::table('terms')->where('id', $term->id)->update(['term_order' => $order]);
        }

        // Fallback: if any term has null order, assign by id ascending after existing max
        $maxAssigned = (int) DB::table('terms')->max('term_order');
        $next = $maxAssigned > 0 ? $maxAssigned + 1 : 1;
        $nullTerms = DB::table('terms')->whereNull('term_order')->orderBy('id')->get(['id']);
        foreach ($nullTerms as $t) {
            DB::table('terms')->where('id', $t->id)->update(['term_order' => $next++]);
        }
    }

    public function down(): void
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('term_order');
        });
    }
};

