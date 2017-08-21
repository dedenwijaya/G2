<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class FasilitasRefleksi extends Model
{
    //
    protected $table = 'fasilitas_refleksi';
    public $timestamps = false;
    
    public static function getOwner($id) {
        return DB::table('fasilitas_refleksi')->where('id_fasilitas', $id)->pluck('owner');;
    }
    
    public static function getTerapis($id) {
        return DB::table('fasilitas_refleksi')->where('id_fasilitas', $id)->pluck('terapis');;
    }
    
    public static function updateOwner($id, $price) {
        DB::table('fasilitas_refleksi')->where('id_fasilitas', $id)->update(['owner' => $price]);;
    }
                                        
    public static function updateTerapis($id, $price) {
        DB::table('fasilitas_refleksi')->where('id_fasilitas', $id)->update(['terapis' => $price]);;
    }
                                        
    public static function deleteItem($id) {        
        DB::table('fasilitas_refleksi')->where('id_fasilitas', $id)->delete();
    }
}