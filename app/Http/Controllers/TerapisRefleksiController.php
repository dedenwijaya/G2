<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator, Input;
use App\TransaksiRefleksi;
use App\TerapisRefleksi;
use App\Gelang;
use App\Absen;
use App\Periode;
use App\Fasilitas;
use App\FasilitasRefleksi;
use Auth;
use App\GelangCustomer;
use App\HistoriPelanggan;

class TerapisRefleksiController extends Controller{
    //
    public function menuTerapis(){
        return view('terapisrefleksi.terapis-menu')
            ->with('nama', '')
            ->with('success', '')
            ->with('saldo', '');;
    }

    public function konfirmasiNaik(){
        
        if (Periode::activeExist() == 1) {
            return view('terapisrefleksi.naik-pelanggan')
                ->with('nama', '')
                ->with('success', '')
                ->with('saldo', '');;
        
        } else {
            return view('terapisrefleksi.terapis-menu')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }   
    }

    public function konfirmasiPelanggan(){
        
        if(Periode::activeExist() == 1) {
            
            $rules = array(
            'noGelang' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->fails()){
                return view('terapisrefleksi.naik-pelanggan')
                    ->withErrors($validator)
                    ->with('nama', '')
                    ->with('success', '')
                    ->with('saldo', '');;
            }else{
                $noGelang = Input::get('noGelang');
                $result = Gelang::checkAvailable($noGelang);
                
                if($result == 0) {
                    return view('terapisrefleksi.naik-pelanggan')
                        ->with('nama', '')
                        ->withErrors("Nomor kartu pelanggan belum dipakai")
                        ->with('success', '')
                        ->with('saldo', '');;
                } 
                else {
                    if(TransaksiRefleksi::notPaid($noGelang) > 0) {
                        return view('terapisrefleksi.naik-pelanggan')
                            ->with('nama', '')
                            ->withErrors("Transaksi refleksi sebelumnya belum dibayar")
                            ->with('success', '')
                            ->with('saldo', '');;
                    } else{
                        return view('terapisrefleksi.naik-terapis')
                            ->with('nama', $noGelang)
                            ->with('success', '')
                            ->with('saldo', Gelang::getSaldo($noGelang));;          
                    }
                }
            } 
        } else {
            return view('terapisrefleksi.naik-pelanggan')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        } 
    }

    public function terapisNaik()
    {
        return view('terapisrefleksi.naik-pelanggan')
            ->with('nama', '')
            ->with('success', '')
            ->with('saldo', '');;
    }

    public function autoKonfirmasiTerapis(){
        
        $noGelang = Input::get('noGelang');

        if(Periode::activeExist() == 1) {

            $rules = array(
            'noKartu' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->fails()){
                return view('terapisrefleksi.naik-terapis')
                    ->withErrors($validator)
                    ->with('nama', $noGelang)
                    ->with('success', '')
                    ->with('saldo', '');;
            }
            else{
                $noKartu = Input::get('noKartu');
                $result = TerapisRefleksi::countExist($noKartu);
                
                if(TransaksiRefleksi::getTerapis($noGelang) != NULL) {
                    return view('terapisrefleksi.naik-terapis')
                        ->with('nama', $noGelang)
                        ->withErrors("Transaksi refleksi sedang berjalan")
                        ->with('success', '')
                        ->with('saldo', '');;        
                }
            
                if($result > 0) { 
                    
                    //cek terapis transaksi sebelumnya sudah dibayar atau belum
                    if(TransaksiRefleksi::checkNotPaid($noKartu) > 0)
                        return view('terapisrefleksi.naik-terapis')
                        ->with('nama', $noGelang)
                            ->withErrors("Transaksi refleksi sebelumnya belum dibayar")
                            ->with('success', '')
                            ->with('saldo', '');;
                        
                    $data = array('noKartu' => $noKartu, 'noGelang' => $noGelang);
                    
                    $no_kartu = TerapisRefleksi::getNoKartu($noKartu);
                                 
                    if($noKartu == $no_kartu) {
                        TransaksiRefleksi::start($noKartu, $noGelang);
                        Absen::absen($noKartu);
                        $start_time = date('H:i:s');
                        return view('terapisrefleksi.terapis-hasil')
                            ->with('nama', '')
                            ->with('success', 'BERHASIL')
                            ->with('saldo', '')
                            ->with('start', $start_time);;
                    }
                    // return view('konfirmasiTerapis')->with($data);     
                } else {
                    return view('terapisrefleksi.naik-terapis')
                        ->with('nama', $noGelang)
                        ->withErrors("Nomor terapis tidak terdaftar")
                        ->with('success', '')
                        ->with('saldo', '');;
                }    
            }
               
        } else {
            return view('terapisrefleksi.naik-terapis')
                ->with('nama', $noGelang)
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }  
    }

    public function konfirmasiSelesai(){
        
        $noKartu = Input::get('noKartu');
        $noGelang = Input::get('noGelang');
        
        $data = array('noKartu' => $noKartu, 'noGelang' => $noGelang);
        
        $no_kartu = TerapisRefleksi::getNoKartu($noKartu);
                     
        if($noKartu == $no_kartu) {
            TransaksiRefleksi::start($noKartu, $noGelang);
            Absen::absen($noKartu);
            return view('terapis-hasil')
                ->with('nama', '')
                ->with('success', 'Berhasil')
                ->with('saldo', '');;
        }
    }

    public function menuTerapisStop() {
        if(Periode::activeExist() == 1) {
            return view('terapisrefleksi.stop-terapis')
                ->with('nama', '')
                ->with('success', '')
                ->with('saldo', '');;
        } else{
            return view('terapisrefleksi.terapis-menu')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }
    }

    public function terapisStop(){
        $rules = array(
            'password' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()){
            return view('terapisrefleksi.stop-terapis')
                ->withErrors($validator)
                ->with('nama', '')
                ->with('success', '')
                ->with('saldo', '');;
        }

        $idTerapis = Input::get('password');
        $result = TransaksiRefleksi::checkAvailable($idTerapis);
        
        if($result == 0) {
            return view('terapisrefleksi.stop-terapis')
                ->with('nama', '')
                ->withErrors("Password terapis salah")
                ->with('saldo', '')
                ->with('success', '');
        } else {
            $result = TerapisRefleksi::countExist($idTerapis);
            if($result > 0) { 
                
                $start = TransaksiRefleksi::getStart($idTerapis);
                $end = date('Y-m-d H:i:s');
        
                list($date1, $time1) = preg_split('/[ ]/', $start);
                list($date2, $time2) = preg_split('/[ ]/', $end);
                
                list($hour1, $minute1, $second1) = preg_split('/[:]/', $time1);
                list($hour2, $minute2, $second2) = preg_split('/[:]/', $time2);
                
                if ($hour1 > $hour2) {
                    $hour2 += 24;
                }
    
                $duration1 = 60 * $hour1 + $minute1;
                $duration2 = 60 * $hour2 + $minute2;
                $duration = $duration2 - $duration1;
       
                $harga = 0;
                $refund = 0;
                $ownerPersentage = FasilitasRefleksi::getOwner(1);
                $terapisPersentage = FasilitasRefleksi::getTerapis(1);

                if ($duration >= 3) {
                    $hargarefleksi = Fasilitas::getHarga('Reflexy', '3');
                    $harga += $hargarefleksi;
                    
                    $siklus = floor(($duration - 3) / 10);
                    if ($siklus >= 1){
                        $hargartambah = Fasilitas::getHarga('Reflexy', '+ 10');
                        $harga += $hargartambah * $siklus;                        
                    }

                    $temp = ($harga * $ownerPersentage) / 100;  
                    $refund = $harga - $temp;
                    $harga = $temp;
                }
                
                $data = array('start'=>$time1, 'end'=>$time2);

                TransaksiRefleksi::stop($idTerapis, $end, $duration, $harga, $refund);
                return view('terapisrefleksi.bayar-terapis')
                    ->with('nama', '')
                    ->with('success', '')
                    ->with('saldo', '')
                    ->with($data);;

            } else {
                return view('terapisrefleksi.stop-terapis')
                    ->with('nama', '')
                    ->withErrors("Nomor terapis tidak terdaftar")
                    ->with('success', '')
                    ->with('saldo', '');;
            }   
        }
    }

    public function konfirmasiStopPelanggan(){
        return view('terapisrefleksi.bayar-terapis')
            ->with('nama', '')
            ->with('success', '')
            ->with('saldo', '');;
    }

    public function bayarTerapis(){
        
        $rules = array(
            'noKartu' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);


        if($validator->fails()){

            return view('terapisrefleksi.bayar-terapis')
                ->withErrors($validator)
                ->with('success', '');
        }
        
        $kartu = Input::get('noKartu');
        if(Gelang::checkAvailable($kartu) == 0) {
            
            return view('terapisrefleksi.bayar-terapis')
                ->withErrors("Nomor kartu belum dipakai")
                ->with('success', '');
        }
        
        if(TransaksiRefleksi::notPaid($kartu) == 0) {
            
            return view('terapisrefleksi.bayar-terapis')
                ->withErrors("Tidak ada tagihan refleksi")
                ->with('success', '');
        }
        
        $transaksi = TransaksiRefleksi::getTransaksi($kartu);
        
            $total = 0;
            $durasi = 0;
            $refund = 0;
            $harga = 0;
            $data = array();
            foreach($transaksi as $terapi) {
                $durasi += $terapi->durasi;
                $harga += $terapi->harga;
                $refund += $terapi->refund;
                $total = $harga + $refund;           
            }
        
        if ($total > Gelang::getSaldo($kartu)) {
            
            return view('terapisrefleksi.bayar-terapis')
                ->withErrors("Saldo tidak mencukupi, Jumlah tagihan : Rp. ".number_format($total).", Saldo yang harus ditop up : Rp. ".number_format($total-Gelang::getSaldo($kartu)))
                ->with('success', 'gagal');
        }
        
        if (TransaksiRefleksi::notEnd($kartu) > 0) {
            
            return view('terapisrefleksi.bayar-terapis')
                ->withErrors("Transaksi refleksi belum dihentikan")
                ->with('success', '');
        }
        
        $saldoAwal = Gelang::getSaldo($kartu);
        
        TransaksiRefleksi::setInactive($kartu);
        Gelang::minSaldo($kartu, $total);
        
        $ldate = date('d-m-Y');
        
        return view('terapisrefleksi.pembayaran-sukses')
            ->with('noKartu', Input::get('noKartu'))
            ->with('saldoAwal', $saldoAwal)
            ->with('total', $total)
            ->with('harga', $harga)
            ->with('sisaSaldo', Gelang::getSaldo($kartu))
            ->with('refund', $refund)
            ->with('durasi', $durasi)
            ->with('date', $ldate);
    }

    public function menuPelanggan(){

        return view('terapisrefleksi.pelanggan-menu')
            ->with('nama', '')
            ->with('success', '')
            ->with('saldo', '');;
    }

    public function saldoCek(){
        if(Periode::activeExist() == 1) {
            return view('terapisrefleksi.cek-saldo')
                ->with('nama', '')
                ->with('success', '')
                ->with('saldo', '');;
        } else {
            return view('terapisrefleksi.pelanggan-menu')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }      
    }

    public function historiCek(){
        if(Periode::activeExist() == 1) {
            return view('terapisrefleksi.cek-histori')
                ->with('nama', '')
                ->with('success', '')
                ->with('saldo', '');;
        } else {
            return view('terapisrefleksi.pelanggan-menu')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }      
    }

    public function saldoPelanggan(){
        
        if(Periode::activeExist() == 1) {

            $rules = array(
                'noGelang' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->fails()){

                return view('terapisrefleksi.cek-saldo')
                    ->withErrors($validator)
                    ->with('success', '')
                    ->with('nama', '')
                    ->with('saldo', '');
            }

            $noGelang = Input::get('noGelang');
            $result = Gelang::checkAvailable($noGelang);
            
            if($result == 0) {
                return view('terapisrefleksi.cek-saldo')
                    ->with('nama', '')
                    ->withErrors("Nomor kartu pelanggan belum dipakai")
                    ->with('success', '')
                    ->with('saldo', '');;
            } else {
                return view('terapisrefleksi.saldo-pelanggan')
                    ->with('nama', $noGelang)
                    ->with('success', 'SALDO ANDA')
                    ->with('saldo', Gelang::getSaldo($noGelang));;
            }
        } else {
            return view('terapisrefleksi.cek-saldo')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }          
    }

    public function historiPelanggan(){
        if(Periode::activeExist() == 1) {
            $rules = array(
                'noGelang' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->fails()){
                return view('terapisrefleksi.cek-histori')
                    ->withErrors($validator)
                    ->with('success', '')
                    ->with('nama', '')
                    ->with('saldo', '');
            }

            $noGelang = Input::get('noGelang');
            $result = Gelang::checkAvailable($noGelang);
            $id_periode = Periode::getActive();

            if($result == 0) {
                return view('terapisrefleksi.cek-histori')
                    ->with('nama', '')
                    ->withErrors("Nomor kartu pelanggan belum dipakai")
                    ->with('success', '')
                    ->with('saldo', '');;
            } else {
                $gelang = HistoriPelanggan::where('gelang', $noGelang)
                            ->where('id_periode', $id_periode)
                            ->orderBy('tanggal', 'desc')
                            ->get();

                return view('terapisrefleksi.histori-pelanggan')
                    ->with('nama', $noGelang)
                    ->with('success', 'Histori Kartu Anda')
                    ->with('saldo', Gelang::getSaldo($noGelang))
                    ->with('data', $gelang);;
            }
        } else {
            return view('terapisrefleksi.cek-histori')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }
    }

    public function statusTerapis(){
        $transaksi = TransaksiRefleksi::all()->where('active', 1);
        
        return view('terapisrefleksi.status-terapis')
            ->with('transaksi', $transaksi);
    }

    public function statusTerapis1(){
        $transaksi = TransaksiRefleksi::all()->where('active', 1);
        
        return view('terapisrefleksi.status-terapis-1')
            ->with('transaksi', $transaksi);
    }

    public function cekDurasi(){

        return view('terapisrefleksi.cek-durasi')
            ->with('nama', '')
            ->with('success', '')
            ->with('saldo', '');;
    }

    public function durasiHasil(){
        
        if(Periode::activeExist() == 1) {

            $rules = array(
                'noKartu' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->fails()){

                return view('terapisrefleksi.cek-durasi')
                    ->withErrors($validator)
                    ->with('success', '')
                    ->with('nama', '')
                    ->with('saldo', '');
            }

            $noKartu = Input::get('noKartu');
            $result = TransaksiRefleksi::checkAvailable($noKartu);
            
            if($result == 0) {
                return view('terapisrefleksi.cek-durasi')
                    ->with('nama', '')
                    ->withErrors("Nomor kartu terapis belum dipakai")
                    ->with('success', '')
                    ->with('saldo', '');;
            } else {
                return view('terapisrefleksi.cek-durasi-hasil')
                    ->with('nama', $noKartu)
                    ->with('success', 'Cek Durasi Reflexy')
                    ->with('durasi', TransaksiRefleksi::getDuration($noKartu));;
            }
        } else {
            return view('terapisrefleksi.cek-durasi')
                ->with('nama', '')
                ->withErrors('Transaksi belum dibuka')
                ->with('success', '')
                ->with('saldo', '');;
        }          
    }
}