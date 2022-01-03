<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DisposisiSurat;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\JenisSurat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class DisposisiSuratController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }

    public function index(){
        $disposisisurats = DisposisiSurat::all();
        return view('admin/disposisi_surat.index',compact('disposisisurats'));
    }
    public function add(){
        $disposisisurat = DB::table('tb_disposisi_surat')->select(   
        'suratMasukId',
        'pengirimId',
        'penerimaId',
        'statusDisposisi',
        'statusBacaDisposisi')->get();
        
        $suratkeluar = DB::table('tb_surat_keluar')->select( 'id','jenisSuratId','nomorSurat','penerima', 'perihal', 'tujuan','lampiran', 'catatan', 'sifatSurat')->get();
        
        $suratmasuk = DB::table('tb_surat_masuk')->select( 'id','jenisSuratId','nomorSurat','pengirimSurat','perihal', 'tujuan','lampiran', 'catatan','sifatSurat','tanggalSurat','statusTeruskan','statusBaca')->get();
        
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();


        return view('admin/disposisi_surat.add',compact('disposisisurat','jenissurat','suratmasuk','suratkeluar'));
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
            'nm_disposisisurat'   =>  'Nama DisposisiSurat',
            'keterangan'   =>  'keterangan',
      
        ];
        $this->validate($request, [
        ],$messages,$attributes);
       
        DisposisiSurat::create([
            'suratMasukId'=>  $request->jenissurat,
            'pengirimId'=>  $request->suratmasuk,
            'penerimaId'=>  $request->suratkeluar,
            'statusDisposisi'=>  $request->statusdisposisi,
            'statusBacaDisposisi'=>  $request->statusBaca,
          
        ]);

        $notification = array(
            'message' => 'Berhasil, data disposisisurat berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.disposisi_surat')->with($notification);
    }

    public function delete($id){
        DisposisiSurat::where('id',$id)->delete();
        $notification = array(
            'message' => 'Berhasil, data disposisisurat  berhasil dihapus!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.disposisi_surat')->with($notification);
    }
    public function edit($id){
        $data = DisposisiSurat::where('id',$id)->first();
        $suratkeluar = DB::table('tb_surat_keluar')->select( 'id','jenisSuratId','nomorSurat','penerima', 'perihal', 'tujuan','lampiran', 'catatan', 'sifatSurat')->get();
        
        $suratmasuk = DB::table('tb_surat_masuk')->select( 'id','jenisSuratId','nomorSurat','pengirimSurat','perihal', 'tujuan','lampiran', 'catatan','sifatSurat','tanggalSurat','statusTeruskan','statusBaca')->get();
        
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();


        return view('admin/disposisi_surat.edit',compact('data','jenissurat','suratmasuk','suratkeluar'));
    }
    public function update(Request $request, $id){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
            'namaDisposisiSurat'   =>  ' DisposisiSurat',
            'keterangan'   =>  ' Keterangan',
        ];
        $this->validate($request, [
        ],$messages,$attributes);
        
        DisposisiSurat::where('id',$id)->update([
            'suratMasukId'=>  $request->jenissurat,
            'pengirimId'=>  $request->suratmasuk,
            'penerimaId'=>  $request->suratkeluar,
            'statusDisposisi'=>  $request->statusdisposisi,
            'statusBacaDisposisi'=>  $request->statusBaca,
            ]);

            $notification = array(
                'message' => 'Berhasil, data disposisisurat berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.disposisi_surat')->with($notification);
        }
}
