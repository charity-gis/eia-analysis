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
        Schema::create('shapefile_zips', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->unique();
            $table->string('file_path');
            $table->timestamp('extracted_at')->nullable();
            $table->timestamp('converted_to_sql_at')->nullable();
            $table->timestamp('sql_imported_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shapefile_zips');
    }
};
