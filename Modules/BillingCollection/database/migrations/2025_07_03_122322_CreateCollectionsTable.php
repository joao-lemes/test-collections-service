<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('collections')) {
            Schema::create('collections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
                $table->float('amount');
                $table->date('due_date');
                $table->string('description');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
