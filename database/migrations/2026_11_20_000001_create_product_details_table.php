<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('code')->nullable();
            $table->string('name_chu_han')->nullable();
            $table->string('name_han_viet')->nullable();
            $table->string('name_quan_hoa')->nullable();
            $table->longText('decision')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('product_id')->references('id')->on('product')->cascadeOnDelete();
            $table->index(['product_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
