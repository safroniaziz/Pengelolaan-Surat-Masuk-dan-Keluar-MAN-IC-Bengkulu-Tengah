<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\DisposisiSurat;
use Illuminate\Support\Str;



class StafTuSuratMasukController extends Controller
{
    public function index(){
        $surat_masuks = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                        ->join('tb_user','tb_user.id','tb_surat_masuk.penginputId')
                        ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal',
                                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan',
                                'statusBaca','tb_surat_masuk.created_at','namaUser as namaPenginput')
                        ->orderBy('created_at','desc')
                        ->get();
        // return $surat_masuks;
        return view('staf_tu/surat_masuk.index',compact('surat_masuks'));
    }

    public function add(){
        $jenissurat = JenisSurat::select('id','jenisSurat')->get();
        return view('staf_tu/surat_masuk.add',compact('jenissurat'));
    }

    public function post(Request $request){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
            'mimes' => 'The :attribute harus berupa file: :values.',
            'max' => [
                'file' => ':attribute tidak boleh lebih dari :max kilobytes.',
            ],
        ];
        $attributes = [
            'jenisSuratId'   =>  'Jenis Surat',
            'pengirimSurat'   =>  'Pengirim Surat',
            'nomorSurat'   =>  'Nomor Surat',
            'perihal'   =>  'Perihal',
            'tujuan'   =>  'Tujuan',
            'lampiran'   =>  'Lampiran',
            'catatan'   =>  'catatan',
            'sifatSurat'   =>  'Sifat Surat',
            'tanggalSurat'   =>  'Tanggal Surat',
        ];
        $this->validate($request, [
            'jenisSuratId'  =>  'required',
            'pengirimSurat'  =>  'required',
            'nomorSurat'  =>  'required',
            'perihal'  =>  'required',
            'tujuan'  =>  'required',
            'lampiran'  =>  'required|mimes:doc,pdf,docx,jpg|max:1000',
            'catatan'  =>  'required',
            'sifatSurat'  =>  'required',
            'tanggalSurat'  =>  'required',
        ],$messages,$attributes);

        $model = $request->all();
        $model['lampiran'] = null;
        $slug_pengirim = Str::slug($request->pengirimSurat);
        $slug_user = Str::slug(Auth::user()->namaUser);

        if ($request->hasFile('lampiran')) {
            $model['lampiran'] = $slug_pengirim.'-'.$request->tanggalSurat.'-'.$slug_user.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_masuk/'.$slug_pengirim), $model['lampiran']);
        }
        SuratMasuk::create([
            'jenisSuratId'=>  $request->jenisSuratId,
            'nomorSurat'    =>  $request->nomorSurat,
            'pengirimSurat'=>  $request->pengirimSurat,
            'perihal'    =>  $request->perihal,
            'tujuan'=>  $request->tujuan,
            'lampiran'    =>  $model['lampiran'],
            'catatan'=>  $request->catatan,
            'sifatSurat'=>  $request->sifatSurat,
            'tanggalSurat'=>  $request->tanggalSurat,
            'penginputId'   =>  Auth::user()->id,
        ]);

        $notification = array(
            'message' => 'Berhasil, data surat masuk berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('staf_tu.surat_masuk')->with($notification);
    }

    public function detail($id){
        $detail = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                            ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal','tujuan','lampiran',
                                        'catatan','sifatSurat','tanggalSurat','statusTeruskan','statusBaca')
                            ->where('tb_surat_masuk.id',$id)
                            ->first();
        return view('staf_tu/surat_masuk.detail',compact('detail'));
    }

    public function teruskan(Request $request){
        DB::beginTransaction();
        try{
            SuratMasuk::where('id',$request->teruskanId)->update([
                'statusTeruskan'    =>  'sudah',
                'penerusId'         => Auth::user()->id,
            ]);
            $data = SuratMasuk::where('id',$request->teruskanId)->select('pengirimSurat')->first();
            $isi_email = [
                'title' =>  'Pemberitahuan Surat Masuk Baru',
                'body'  =>  'Assalammualaikum, terdapat surat masuk baru dari '."<b>".$data->pengirimSurat."</b>".' yang sudah diteruskan oleh tata usaha ke kepala sekolah'
            ];
            $tujuan = 'agoyunib@gmail.com';
            Mail::to($tujuan)->send(new SendMail($isi_email));
            DB::commit();
            $notification = array(
                'message' => 'Berhasil, data surat masuk berhasil diteruskan!',
                'alert-type' => 'success'
            );
            return redirect()->route('staf_tu.surat_masuk')->with($notification);
        }catch(\Exception $e){
            DB::rollback();
            $notification2 = array(
                'message' => 'Gagal, data surat masuk gagal diteruskan!',
                'alert-type' => 'error'
            );
            return redirect()->route('staf_tu.surat_masuk')->with($notification2);
        }
    }

    public function bacaSurat($id, Request $request){
        // DB::beginTransaction();
        // try {
            Artisan::call('config:cache');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');
            $data = SuratMasuk::where('id',$id)->select('pengirimSurat','lampiran','statusBaca')->first();
            if ($data->statusBaca == "belum") {
                $isi_email = [
                    'title' =>  'Pemberitahuan Surat Masuk ',
                    'body'  =>  'Assalammualaikum, surat masuk dari '."<b>".$data->pengirimSurat."</b>".' sudah dibaca oleh kepala sekolah'
                ];
                $tujuan = 'agoyunib@gmail.com';
                Mail::to($tujuan)->send(new SendMail($isi_email));

                SuratMasuk::where('id',$id)->update([
                    'statusBaca' => 'sudah',
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
            
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $notification = array(
        //         'message' => 'Gagal, surat gagal dibaca!',
        //         'alert-type' => 'error'
        //     );
        //     return redirect()->route('staf_tu.surat_masuk')->with($notification);
        // }
    }
    public function edit($id){
        $data = SuratMasuk::where('id',$id)->first();
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
     
        return view('staf_tu/surat_masuk.edit',compact('data','jenissurat'));
    }
    public function update(Request $request, $id){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
           
        ];
        $this->validate($request, [
        ],$messages,$attributes);


         $model = $request->all();
        $model['lampiran'] = null;
        $slug_user = Str::slug(Auth::user()->namaUser);

        if ($request->hasFile('lampiran')) {
            $model['lampiran'] = $slug_user.'-'.Auth::user()->namaUser.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_masuk/'.$slug_user), $model['lampiran']);
        }
        // return $data;

        SuratMasuk::where('id',$id)->update([
            'jenisSuratId'=>  $request->jenissurat,
            'nomorSurat'    =>  $request->nomorSurat,
            'pengirimSurat'=>  $request->pengirimSurat,
            'perihal'    =>  $request->perihal,
            'tujuan'=>  $request->tujuan,
            'catatan'=>  $request->catatan,
            'sifatSurat'=>  $request->sifatSurat,
            'tanggalSurat'=>  $request->tanggalSurat,
            // 'statusTeruskan'=>  $request->statusTeruskan,
            // 'statusBaca'=>  $request->statusBaca,
            'lampiran'    =>  $model['lampiran'],

            ]);

            $notification = array(
                'message' => 'Berhasil, data surat_masuk berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('staf_tu.surat_masuk')->with($notification);
        }
        public function delete($id){
            SuratMasuk::where('id',$id)->delete();
            $notification = array(
                'message' => 'Berhasil, data surat masuk berhasil dihapus!',
                'alert-type' => 'success'
            );
            return redirect()->route('staf_tu.surat_masuk')->with($notification);
        }
}
