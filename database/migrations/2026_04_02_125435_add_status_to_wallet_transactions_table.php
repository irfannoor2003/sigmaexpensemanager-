<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('wallet_transactions', function (Blueprint $table) {
        // Adding status as a string, defaulting to 'pending'
        $table->string('status')->default('pending')->after('type');
    });
}

public function down(): void
{
    Schema::table('wallet_transactions', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
