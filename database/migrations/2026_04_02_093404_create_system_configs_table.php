<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('system_configs', function (Blueprint $table) {
        $table->id();
        $table->string('key');
        $table->string('value');
        $table->string('month_year');

        // This allows 'grace_period_deadline' for '04-2026' AND '05-2026'
        $table->unique(['key', 'month_year']);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('system_configs');
}
};
