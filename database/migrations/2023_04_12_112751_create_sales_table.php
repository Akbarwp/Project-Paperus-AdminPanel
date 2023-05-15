<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_transaksi');
            $table->float('grand_total')->nullable();
            $table->float('biaya_kirim')->nullable();
            $table->string('status');
            $table->foreignId('user_id')->constrained('users', 'id')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('sales_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('kuantitas');
            $table->float('total');
            $table->float('keuntungan');
            $table->float('diskon')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('sales_id')->constrained('sales', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk', 'id');
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
        Schema::dropIfExists('sales');
    }
}
