<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thiệp cưới online: mỗi thiệp lưu nội dung dạng JSON theo mẫu (config/invitation.php)
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('edit_token', 64)->unique();
            $table->string('template', 50);
            $table->json('data');
            $table->timestamps();
        });

        Schema::create('invitation_wishes', function (Blueprint $table) {
            $table->id();
            $table->uuid('invitation_id')->index();
            $table->string('name', 100);
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('invitation_rsvps', function (Blueprint $table) {
            $table->id();
            $table->uuid('invitation_id')->index();
            $table->string('name', 100);
            $table->string('phone', 30)->nullable();
            $table->boolean('attending');
            $table->unsignedTinyInteger('guests')->default(1);
            $table->string('side', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_rsvps');
        Schema::dropIfExists('invitation_wishes');
        Schema::dropIfExists('invitations');
    }
};
