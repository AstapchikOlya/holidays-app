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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('holiday_id');
            $table->date('date');
            $table->integer('count')->default(0);
            $table->boolean('is_additional_day_off')->default(false);
            $table->timestamps();

            $table->foreign('holiday_id')
                ->references('id')
                ->on('holidays')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stats', function (Blueprint $table) {
            $table->dropForeign(['holiday_id']);
        });

        Schema::dropIfExists('stats');
    }
};
