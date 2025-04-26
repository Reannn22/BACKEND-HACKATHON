<?php

namespace App\Models;

use App\Traits\WeightFormatter;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use WeightFormatter;

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'merek_barang',
        'tahun_pengadaan',
        'deskripsi_barang',
        'jumlah_barang',
        'jumlah_tersedia',
        'id_kategori',
        'id_lokasi',
        'id_admin',
        'is_dibawa',
        'berat_barang',
        'warna_barang',
        'kondisi_barang',
        'status_barang',
        'harga_perolehan',
    ];

    protected $appends = ['formatted_weight'];
    protected $with = ['foto_barang'];

    protected function getFormattedWeightAttribute()
    {
        return $this->formatWeight($this->berat_barang);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $this->loadMissing(['foto_barang', 'category', 'location', 'admin']);
        
        return [
            'id' => $array['id'],
            'nama_barang' => $array['nama_barang'],
            'kode_barang' => $array['kode_barang'],
            'merek_barang' => $array['merek_barang'],
            'tahun_pengadaan' => $array['tahun_pengadaan'],
            'foto_barang' => $this->foto_barang ? $this->foto_barang->map(function($foto) {
                return [
                    'id' => $foto->id,
                    'foto_path' => asset('storage/foto_barang/' . $foto->foto_path)
                ];
            }) : [],
            'deskripsi_barang' => $array['deskripsi_barang'],
            'jumlah_barang' => $array['jumlah_barang'],
            'jumlah_tersedia' => $array['jumlah_tersedia'],
            'kategori' => $this->category ? [
                'id' => $this->category->id,
                'nama_kategori' => $this->category->nama_kategori
            ] : null,
            'lokasi' => $this->location ? [
                'id' => $this->location->id,
                'nama_lokasi' => $this->location->nama_lokasi,
                'gedung' => $this->location->gedung,
                'ruangan' => $this->location->ruangan
            ] : null,
            'admin' => $this->admin ? [
                'id' => $this->admin->id,
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'role' => $this->admin->role,
                'no_hp' => $this->admin->no_hp,
                'created_at' => $this->admin->created_at,
                'updated_at' => $this->admin->updated_at
            ] : null,
            'is_dibawa' => $array['is_dibawa'],
            'berat_barang' => $array['formatted_weight'],
            'warna_barang' => $array['warna_barang'],
            'kondisi_barang' => $array['kondisi_barang'],
            'status_barang' => $array['status_barang'],
            'harga_perolehan' => $array['harga_perolehan'],
            'created_at' => $array['created_at'],
            'updated_at' => $array['updated_at']
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }

    public function foto_barang()
    {
        return $this->hasMany(FotoBarang::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_lokasi');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->load('foto_barang');
        });
    }
}
