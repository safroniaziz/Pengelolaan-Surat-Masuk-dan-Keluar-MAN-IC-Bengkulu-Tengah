<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\DisposisiSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;

class PimpinanSuratMasukController extends Controller
{
    public function index(){
        if (Auth::user()->jabatan->namaJabatan == "Kepala Sekolah") {
            $surat_masuks = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                        ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal',
                                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan',
                                'statusBaca','isDisposisi','tb_surat_masuk.created_at')
                        ->where('statusTeruskan','sudah')
                        ->orderBy('created_at','desc')
                        ->get();
        }else {
            $surat_masuks = DisposisiSurat::join('tb_surat_masuk','tb_surat_masuk.id','tb_disposisi_surat.suratMasukId')
                                            ->join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                                            ->select('tb_surat_masuk.id','tb_disposisi_surat.id as disposisiId','jenisSurat','pengirimSurat','nomorSurat','perihal',
                                                    'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusDisposisi',
                                                    'tb_disposisi_surat.statusBacaDisposisi','tb_surat_masuk.created_at')
                                            ->where('penerimaId',Auth::user()->id)
                                            ->orderBy('tb_surat_masuk.created_at','desc')
                                            ->get();
        }
        $pimpinans = User::join('tb_jabatan','tb_jabatan.id','tb_user.jabatanId')
                            ->select('tb_user.id','namaUser','namaJabatan')
                            ->where('hakAkses','pimpinan')
                            ->where('namaJabatan','!=','Kepala Sekolah')
                            ->orderBy('id')
                            ->get();
        return view('pimpinan/surat_masuk.index',compact('surat_masuks','pimpinans'));
    }

    public function disposisikan(Request $request){
        if ($request->disposisi == "tidak") {
            SuratMasuk::where('id',$request->suratId)->update([
                'isDisposisi'   =>  '0',
            ]);
            $notification = array(
                'message' => 'Berhasil, tidak ada proses disposisi surat masuk!',
                'alert-type' => 'success'
            );
            return redirect()->route('pimpinan.surat_masuk')->with($notification);
        }else {
            DB::beginTransaction();
            try {
                $penerima = User::where('id',$request->penerimaId)->select('email')->first();
                DisposisiSurat::create([
                    'suratMasukId'          =>  $request->suratId,
                    'pengirimId'            =>  Auth::user()->id,
                    'penerimaId'            =>  $request->penerimaId,
                    'statusBacaDisposisi'   =>  'belum',
                    'statusDisposisi'       =>  'menunggu',
                ]);
                SuratMasuk::where('id',$request->suratId)->update([
                    'isDisposisi'           =>  1,
                ]);
                $isi_email = [
                    'title' =>  'Pemberitahuan Disposisi Surat ',
                    'body'  =>  'Assalammualaikum, anda menerima disposisi surat masuk dari '."<b>".Auth::user()->jabatan->namaJabatan."</b>".' atas nama '."<b>".Auth::user()->namaUser."</b>".' , silahkan buka aplikasi untuk membaca surat'
                ];
                $tujuan = $penerima->email;
                Mail::to($tujuan)->send(new SendMail($isi_email));
                DB::commit();
                $notification = array(
                    'message' => 'Berhasil, data surat masuk berhasil didisposisikan!',
                    'alert-type' => 'success'
                );
                return redirect()->route('pimpinan.surat_masuk')->with($notification);
            } catch (\Exception $e) {
                DB::rollback();
                $notification = array(
                    'message' => 'Gagal, data surat masuk gagal didisposisikan!',
                    'alert-type' => 'error'
                );
                return redirect()->route('pimpinan.surat_masuk')->with($notification);
            }
        }
    }

    public function disposisikan2(Request $request){
        // return $request->all();
        if ($request->disposisi == "tidak") {
            DisposisiSurat::where('id',$request->disposisiId)->update([
                'statusDisposisi'   =>  'selesai',
            ]);
            $notification = array(
                'message' => 'Berhasil, tidak ada proses disposisi surat masuk!',
                'alert-type' => 'success'
            );
            return redirect()->route('pimpinan.surat_masuk')->with($notification);
        }else {
            DB::beginTransaction();
            try {
                $disposisi = DisposisiSurat::where('id',$request->disposisiId)->first();
                // return $disposisi;
                $penerima = User::where('id',$request->penerimaId)->select('email')->first();
                DisposisiSurat::create([
                    'suratMasukId'          =>  $disposisi->suratMasukId,
                    'pengirimId'            =>  Auth::user()->id,
                    'penerimaId'            =>  $request->penerimaId,
                    'statusBacaDisposisi'   =>  'belum',
                    'statusDisposisi'       =>  'disposisi',
                ]);
                $isi_email = [
                    'title' =>  'Pemberitahuan Disposisi Surat ',
                    'body'  =>  'Assalammualaikum, anda menerima disposisi surat masuk dari '."<b>".Auth::user()->jabatan->namaJabatan."</b>".' atas nama '."<b>".Auth::user()->namaUser."</b>".' , silahkan buka aplikasi untuk membaca surat'
                ];
                $tujuan = $penerima->email;
                Mail::to($tujuan)->send(new SendMail($isi_email));
                DB::commit();
                $notification = array(
                    'message' => 'Berhasil, data surat masuk berhasil didisposisikan!',
                    'alert-type' => 'success'
                );
                return redirect()->route('pimpinan.surat_masuk')->with($notification);
            } catch (\Exception $e) {
                DB::rollback();
                $notification = array(
                    'message' => 'Gagal, data surat masuk gagal didisposisikan!',
                    'alert-type' => 'error'
                );
                return redirect()->route('pimpinan.surat_masuk')->with($notification);
            }
        }
    }

    public function detail($id){
        $surat = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                            ->leftJoin('tb_user','tb_user.id','tb_surat_masuk.penerusId')
                            ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal',
                                    'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan',
                                    'statusBaca','isDisposisi','tb_surat_masuk.created_at','namaUser as namaPenerus','waktuTeruskan')
                            ->where('tb_surat_masuk.id',$id)
                            ->first();
        $kepsek = User::join('tb_jabatan','tb_jabatan.id','tb_user.jabatanId')
                        ->select('namaUser','tb_user.id')
                        ->where('namaJabatan','Kepala Sekolah')
                        ->where('hakAkses','pimpinan')
                        ->where('status','aktif')
                        ->first();
        $disposisis = DisposisiSurat::join('tb_surat_masuk','tb_surat_masuk.id','tb_disposisi_surat.suratMasukId')
                                    ->where('suratMasukId',$id)->get();
        return view('pimpinan/surat_masuk.detail',compact('surat','disposisis','kepsek'));
    }

    public function bacaSurat($id, Request $request){
        DB::beginTransaction();
        try {
            Artisan::call('config:cache');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');
            $data = SuratMasuk::where('id',$id)->select('pengirimSurat','lampiran','statusBaca','penginputId')->first();
            if ($data->statusBaca == "belum") {
                $isi_email = [
                    'title' =>  'Pemberitahuan Surat Masuk ',
                    'body'  =>  'Assalammualaikum, surat masuk dari '."<b>".$data->pengirimSurat."</b>".' sudah dibaca oleh kepala sekolah'
                ];
                $tujuan = User::where('id',$data->penginputId)->select('email')->first();
                // $tujuan = 'safroni.aziz@unib.ac.id';
                Mail::to($tujuan->email)->send(new SendMail($isi_email));

                SuratMasuk::where('id',$id)->update([
                    'statusBaca' => 'sudah',
                ]);
                $user = $data->pengirimSurat;
                $file = $data->lampiran;
                $path = 'upload/surat_masuk/'.\Illuminate\Support\Str::slug($user).'/'.$file;
                return Response::make(file_get_contents($path), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="'.$file.'"'
                ]);
                DB::commit();
            }else {
                $user = $data->pengirimSurat;
                $file = $data->lampiran;
                $path = 'upload/surat_masuk/'.\Illuminate\Support\Str::slug($user).'/'.$file;
                return Response::make(file_get_contents($path), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="'.$file.'"'
                ]);
                DB::commit();
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                'message' => 'Gagal, surat gagal dibaca!',
                'alert-type' => 'error'
            );
            return redirect()->route('pimpinan.surat_masuk')->with($notification);
        }
    }

    public function bacaSurat2($disposisiId, Request $request){
        DB::beginTransaction();
        try {
            Artisan::call('config:cache');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');
            $data = DisposisiSurat::join('tb_surat_masuk','tb_surat_masuk.id','tb_disposisi_surat.suratMasukId')
                                    ->join('tb_user','tb_user.id','tb_disposisi_surat.pengirimId')
                                    ->join('tb_user as penerima','penerima.id','tb_disposisi_surat.penerimaId')
                                    ->join('tb_jabatan','tb_jabatan.id','penerima.jabatanId')
                                    ->select('pengirimSurat','lampiran','statusBacaDisposisi','namaJabatan','tb_user.email','penerima.namaUser as namaPenerima','penerima.id as idPenerima')
                                    ->where('tb_disposisi_surat.id',$disposisiId)
                                    ->first();
            // return $data;
            if ($data->statusBacaDisposisi == "belum") {
                $isi_email = [
                    'title' =>  'Pemberitahuan Surat Masuk ',
                    'body'  =>  'Assalammualaikum, disposisi surat masuk dari '."<b>".$data->pengirimSurat."</b>".' sudah dibaca oleh '."<b>".$data->namaPenerima."</b>".' selaku '."<b>".$data->namaJabatan."</b>".''
                ];
                $tujuan = $data->email;
                Mail::to($tujuan)->send(new SendMail($isi_email));

                DisposisiSurat::where('id',$disposisiId)->update([
                    'statusBacaDisposisi' => 'sudah',
                ]);
                $user = $data->pengirimSurat;
                $file = $data->lampiran;
                $path = 'upload/surat_masuk/'.\Illuminate\Support\Str::slug($user).'/'.$file;
                
                DB::commit();
                return response()->file($path);
            }else {
                $user = $data->pengirimSurat;
                $file = $data->lampiran;
                $path = 'upload/surat_masuk/'.\Illuminate\Support\Str::slug($user).'/'.$file;
                DB::commit();
                return response()->file($path);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                'message' => 'Gagal, surat gagal dibaca!',
                'alert-type' => 'error'
            );
            return redirect()->route('pimpinan.surat_masuk')->with($notification);
        }
    }
}
