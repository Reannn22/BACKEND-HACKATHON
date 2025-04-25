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
        'lokasi_barang',
        'id_kategori',
        'is_dibawa',
        'berat_barang'
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
        return [
            'nama_barang' => $array['nama_barang'],
            'kode_barang' => $array['kode_barang'],
            'merek_barang' => $array['merek_barang'],
            'tahun_pengadaan' => $array['tahun_pengadaan'],
            'foto_barang' => $array['foto_barang'],
            'deskripsi_barang' => $array['deskripsi_barang'],
            'jumlah_barang' => $array['jumlah_barang'],
            'jumlah_tersedia' => $array['jumlah_tersedia'],
            'lokasi_barang' => $array['lokasi_barang'],
            'id_kategori' => $array['id_kategori'],
            'is_dibawa' => $array['is_dibawa'],
            'berat_barang' => $array['formatted_weight'],
            'created_at' => $array['created_at'],
            'updated_at' => $array['updated_at'],
            'id' => $array['id']
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->load('foto_barang');
        });
    }
}
