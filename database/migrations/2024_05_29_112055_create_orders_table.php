<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
            public function up()
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // Add this line
                $table->string('name')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('product_name')->nullable();
                $table->string('quantity')->nullable();
                $table->string('price')->nullable();
                $table->enum('payment_status', ['1', '2', '3', '4'])->comment('1=menunggu pembayaran, 2=sudah dibayar, 3=kadaluarsa, 4=batal');
                $table->string('snap_token', 36)->nullable();
                $table->string('status')->nullable();
                $table->timestamps();

                // Add foreign key constraint if necessary
                // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
