<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateZodiacSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zodiac_signs', function (Blueprint $table) {
            $table->id();
			$table->string('Aries');
			$table->string('Taurus');
			$table->string('Gemini');
			$table->string('Cancer');
			$table->string('Leo');
			$table->string('Virgo');
			$table->string('Libra');
			$table->string('Scorpio');
			$table->string('Sagittarius');
			$table->string('Capricorn');
			$table->string('Aquarius');
			$table->string('Pisces');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zodiac_signs');
    }
}
