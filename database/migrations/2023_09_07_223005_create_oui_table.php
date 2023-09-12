<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOuiTable extends Migration
{
    public function up()
    {
        Schema::create('oui', function (Blueprint $table) {
            $table->id();
            $table->string('Registry');
            $table->string('Assignment');
            $table->string('Organization Name');
            $table->string('Organization Address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oui');
    }
}
