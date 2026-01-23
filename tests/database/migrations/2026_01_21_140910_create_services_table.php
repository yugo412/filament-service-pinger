<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinger_services', function (Blueprint $table): void {
            $table->id();

            $table->string('name');
            $table->string('url');

            $table->string('method', 10)->default('GET');
            $table->unsignedSmallInteger('expected_status')->default(200);
            $table->unsignedInteger('timeout')->default(3000)->comment('In second');
            $table->unsignedInteger('interval')->default(60)->comment('In second');

            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_up')->default(false);
            $table->unsignedSmallInteger('last_status_code')->nullable();
            $table->unsignedInteger('last_response_time')->nullable()->comment('In millisecond');
            $table->timestamp('last_checked_at')->nullable()->index();
            $table->dateTime('next_check_at')->nullable()->index();

            $table->json('payload')->default(null);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinger_services');
    }
};
