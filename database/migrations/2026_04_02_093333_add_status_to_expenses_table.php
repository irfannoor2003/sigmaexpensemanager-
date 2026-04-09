<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('expenses', function (Blueprint $table) {
        // status: pending (default), approved, rejected, on_hold
        $table->enum('status', ['pending', 'approved', 'rejected', 'on_hold'])
              ->default('pending')
              ->after('amount');

        // hr_remarks: To explain why a bill was rejected or put on hold
        $table->text('hr_remarks')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('expenses', function (Blueprint $table) {
        $table->dropColumn(['status', 'hr_remarks']);
    });
}
};
