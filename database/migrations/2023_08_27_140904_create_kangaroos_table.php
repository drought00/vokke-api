<?php

use App\Enumerators\KangarooEnumerator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kangaroos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('nickname')->nullable();
            $table->float('weight');
            $table->float('height');
            $table->enum('gender', KangarooEnumerator::GENDERS);
            $table->string('color')->nullable();
            $table->enum('friendliness', KangarooEnumerator::FRIENDLINESS);
            $table->date('birthday');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kangaroos');
    }
};
