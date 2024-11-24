<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // id - int(10) increment
            $table->unsignedBigInteger('event_id'); // event_id - int(11)
            $table->string('event_date', 10); // event_date - varchar(10)
            $table->unsignedInteger('ticket_adult_price'); // ticket_adult_price - int(11)
            $table->unsignedInteger('ticket_adult_quantity'); // ticket_adult_quantity - int(11)
            $table->unsignedInteger('ticket_kid_price'); // ticket_kid_price - int(11)
            $table->unsignedInteger('ticket_kid_quantity'); // ticket_kid_quantity - int(11)
            $table->string('barcode', 120)->unique(); // barcode - varchar(120) unique
            $table->unsignedInteger('equal_price'); // equal_price - int(11)
            $table->timestamp('created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
