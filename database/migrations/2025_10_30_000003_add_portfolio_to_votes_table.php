<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('portfolio_id')->after('id')->constrained()->onDelete('cascade');
            $table->unique(['user_id', 'portfolio_id'], 'one_vote_per_portfolio');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['portfolio_id']);
            $table->dropUnique('one_vote_per_portfolio');
            $table->dropColumn('portfolio_id');
        });
    }
};
