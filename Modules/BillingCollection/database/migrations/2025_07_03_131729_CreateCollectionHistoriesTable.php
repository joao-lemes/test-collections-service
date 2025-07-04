<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('collection_histories')) {
            Schema::create('collection_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade');
                $table->enum('type', ['create']);
                $table->enum('status', ['created', 'notification_sent']);
                $table->json('payload');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_histories');
    }
};
