<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_positions', function (Blueprint $table) {
            $table->id();
            $table->string('category_id');
            $table->date('date');
            $table->integer('value');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_positions');
    }
};
