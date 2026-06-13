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
        Schema::create('meta_catalog_queue', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('product_id');
            $table->enum('action', ['create', 'update', 'delete']);
            $table->enum('status', ['pending', 'dispatched', 'failed'])->default('pending');
            $table->string('error')->nullable();
            $table->foreign('meta_catalog_batch_id')
                ->references('id')
                ->on('meta_catalog_batch')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_catalog_queue');
    }
};
