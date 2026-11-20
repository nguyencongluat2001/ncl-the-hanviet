<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('code');
        });

        DB::table('product_details')
            ->orderBy('product_id')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->groupBy('product_id')
            ->each(function ($details) {
                foreach ($details->values() as $index => $detail) {
                    DB::table('product_details')->where('id', $detail->id)->update(['sort_order' => $index + 1]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
