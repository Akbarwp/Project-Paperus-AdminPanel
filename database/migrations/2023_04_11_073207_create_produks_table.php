<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('finishing', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });
        
        Schema::create('bahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->float('berat')->nullable();
            $table->string('satuan_berat')->nullable();
            $table->timestamps();
        });
        
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('foto')->nullable();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->float('modal');
            $table->float('harga');
            $table->integer('stok')->nullable()->default(0);
            $table->boolean('status');
            $table->float('panjang')->nullable();
            $table->float('lebar')->nullable();
            $table->float('tinggi')->nullable();
            $table->string('satuan_ukuran')->nullable();
            $table->foreignId('bahan_id')->constrained('bahan', 'id')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('restock', function (Blueprint $table) {
            $table->id();
            $table->integer('stok');
            $table->date('tanggal');
            $table->foreignId('produk_id')->constrained('produk', 'id')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk', 'id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('kategori_id')->constrained('kategori', 'id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('finishing_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk', 'id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('finishing_id')->constrained('finishing', 'id')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('bahan');
    }
}
