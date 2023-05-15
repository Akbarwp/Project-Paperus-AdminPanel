<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bahan;
use App\Models\Finishing;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\KategoriProduk;
use App\Models\UserIdentitas;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin123')
        ]);

        UserIdentitas::create([
            'user_id' => '1',
            'nama_lengkap' => 'Admin Admin',
            'telepon' => '081',
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $adminUser->assignRole($adminRole);

        Kategori::create([
            'nama' => 'Box'
        ]);
        Kategori::create([
            'nama' => 'Parcel'
        ]);

        Finishing::create([
            'nama' => 'Laminasi Glossy'
        ]);
        Finishing::create([
            'nama' => 'Laminasi Doff'
        ]);

        Bahan::create([
            'nama' => 'Ivory',
            'berat' => '300',
            'satuan_berat' => 'GSM'
        ]);
        Bahan::create([
            'nama' => 'Ivory',
            'berat' => '260',
            'satuan_berat' => 'GSM'
        ]);

        Produk::create([
            'nama' => 'Bluder Box',
            'harga' => '5000',
            'stok' => '10',
            'status' => '1',
            'bahan_id' => '1',
            'panjang' => '24',
            'lebar' => '12',
            'tinggi' => '7',
            'satuan_ukuran' => 'cm',
        ]);
    }
}
