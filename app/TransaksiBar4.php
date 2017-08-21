<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Periode;

class TransaksiBar4 extends Model
{
    //
    protected $table = 'transaksi_bar_4';
    public $timestamps = false;
    
    public static function add($id_item, $jumlah, $noGelang)
    {

        $transaksi = new TransaksiBar4;
        
        $transaksi->no_gelang = $noGelang;
        $transaksi->id_item = $id_item;
        $transaksi->jumlah = $jumlah;
        $transaksi->active = 1;
        $transaksi->harga_total = Item4::getPrice($id_item) * $jumlah;
        $transaksi->periode = Periode::getActive();
        
        $transaksi->save();
    }
    
    public static function getTransaksi($noGelang) {
     
        return DB::table('transaksi_bar_4')->where('no_gelang', $noGelang)->where('active', 1)->get();
    }
    
    public static function getTotalTransaksiOn($periode) {
     
        return DB::table('transaksi_bar_4')->where('periode', $periode)->sum('harga_total');
    }
    
    public static function setInactive($noGelang) {
        DB::table('transaksi_bar_4')->where('no_gelang', $noGelang)->update(['active' => false]);
    }
    
    public static function getTotalLastPeriod() {
        return DB::table('transaksi_bar_4')->where('periode', Periode::getLastId())->count(); 
    }
    
    public static function clear() {
        DB::table('transaksi_bar_4')->truncate();
    }
}