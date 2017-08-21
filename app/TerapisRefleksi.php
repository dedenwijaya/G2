<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TerapisRefleksi extends Model 
{
    protected $table = 'therapist_refleksi';
    public $timestamps = false;
    
    public static function countExist($noKartu)
    {
        $exist = DB::table('therapist_refleksi')->where('no_kartu', $noKartu)->count();;
        return $exist;
            
    }
    
    public static function getNoKartu($noKartu) {
        $no_kartu = DB::table('therapist_refleksi')
                     ->where('no_kartu', $noKartu)->pluck('no_kartu');
        
        return $no_kartu;   
    }
    
    public static function add($noKartu, $nama) {

        $terapis = new TerapisRefleksi;

        $terapis->no_kartu = $noKartu;
        $terapis->nama = $nama;

        $terapis->save();
    }

    public static function getNama($noKartu) {
        $nama = DB::table('therapist_refleksi')
                     ->where('no_kartu', $noKartu)->pluck('nama');
        
        return $nama;   
    }
}