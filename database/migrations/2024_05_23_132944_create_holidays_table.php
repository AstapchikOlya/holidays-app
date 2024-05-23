<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const WEEK_DAYS = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

    const WEEK_NUMBERS = [
        1, 2, 3, 4, 'last'
    ];

    const MONTHS = [
        'December', 'January', 'February', 'March', 'April', 'May',
        'June', 'July', 'August', 'September', 'October', 'November'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('day_from')->nullable();
            $table->unsignedTinyInteger('day_to')->nullable();
            $table->enum('week_day', self::WEEK_DAYS)->nullable();
            $table->enum('week_number', self::WEEK_NUMBERS)->nullable();
            $table->enum('month', self::MONTHS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
