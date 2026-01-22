<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yugo\FilamentServicePinger\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinger_service_checks', function (Blueprint $table): void {
            $table->id();

            $table->foreignIdFor(Service::class)->cascadeOnDelete();

            $table->string('url');
            $table->string('method', 10)->default('GET');
            $table->boolean('is_up');
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('response_time')->nullable()->comment('In millisecond');
            $table->string('error_message')->nullable();

            $table->timestamp('checked_at')->index();

            $table->json('payload')->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinger_service_checks');
    }
};
