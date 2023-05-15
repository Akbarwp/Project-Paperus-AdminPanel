<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golongan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->double('gaji_pokok');
            $table->double('tunjangan_istri')->nullable();
            $table->double('tunjangan_anak')->nullable();
            $table->double('tunjangan_transport')->nullable();
            $table->double('tunjangan_makan')->nullable();
            $table->timestamps();
        });

        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->string('nik');
            $table->string('foto');
            $table->string('nama');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('telepon');
            $table->string('agama');
            $table->string('status');
            $table->string('alamat');
            $table->foreignId('golongan_id')->constrained('golongan', 'id')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_lembur');
            $table->integer('jumlah');
            $table->foreignId('pegawai_id')->constrained('pegawai', 'id')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_cuti');
            $table->integer('jumlah');
            $table->foreignId('pegawai_id')->constrained('pegawai', 'id')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->double('jumlah_gaji');
            $table->float('jumlah_lembur');
            $table->double('potongan');
            $table->double('total_gaji_diterima');
            $table->foreignId('pegawai_id')->constrained('pegawai', 'id')->onUpdate('cascade');
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
        Schema::dropIfExists('lembur');
        Schema::dropIfExists('cuti');
        Schema::dropIfExists('penggajian');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('golongan');
    }
}
