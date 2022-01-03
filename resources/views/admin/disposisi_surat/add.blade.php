@extends('layouts.layout')
@section('title', 'Manajemen Klasifikasi Berkas')
@section('login_as', 'Guru')
@section('user-login')
    @if (Auth::check())
    {{ Auth::user()->pegNama }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::check())
    {{ Auth::user()->pegNama }}
    @endif
@endsection
@section('sidebar-menu')
    @include('admin/sidebar')
@endsection
@push('styles')
    <style>
        #selengkapnya{
            color:#5A738E;
            text-decoration:none;
            cursor:pointer;
        }
        #selengkapnya:hover{
            color:#007bff;
        }
    </style>
@endpush
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Sistem Informasi Pengelolaan Surat Masuk dan Keluar MAN IC Bengkulu Tengah
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-primary alert-block text-center" id="keterangan">

                        <strong class="text-uppercase"><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong><br> Silahkan tambahkan usulan kegiatan anda, harap melengkapi data terlebih dahulu agar proses pengajuan usulan tidak ada masalah kedepannya !!
                    </div>
                </div>
                <div class="row">
                    <form action="{{ route('admin.disposisi_surat.post') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="col-md-12">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jenis Surat</label>
                                <select name="jenissurat" class="form-control" id="">
                                    <option disabled>-- pilih Jenis Surat  --</option>
                                    @foreach ($jenissurat as $jenissurat)
                                        <option value="{{ $jenissurat->id }}">{{ $jenissurat->jenisSurat }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('jenissurat'))
                                    <small class="form-text text-danger">{{ $errors->first('jenissurat') }}</small>
                                @endif
                            </div>
                         <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Pengirim Surat</label>
                                <select name="suratmasuk" class="form-control" id="">
                                    <option disabled>-- pilih Pengirim Surat  --</option>
                                    @foreach ($suratmasuk as $suratmasuk)
                                        <option value="{{ $suratmasuk->id }}">{{ $suratmasuk->pengirimSurat }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('suratmasuk'))
                                    <small class="form-text text-danger">{{ $errors->first('suratmasuk') }}</small>
                                @endif
                            </div>

                           <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Penerima Surat</label>
                                <select name="suratkeluar" class="form-control" id="">
                                    <option disabled>-- pilih Penerima Surat  --</option>
                                    @foreach ($suratkeluar as $suratkeluar)
                                        <option value="{{ $suratkeluar->id }}">{{ $suratkeluar->penerima }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('suratkeluar'))
                                    <small class="form-text text-danger">{{ $errors->first('suratkeluar') }}</small>
                                @endif
                            </div>
                             <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Status disposisi Surat</label>
                                <select name="statusdisposisi" class="form-control">
                                    <option disabled>-- pilih Status disposisi Surat --</option>
                                    <option value="sudah">Sudah</option>
                                    <option value="belum">Belum</option>
                                </select>
                                @if ($errors->has('statusdisposisi'))
                                    <small class="form-text text-danger">{{ $errors->first('statusdisposisi') }}</small>
                                @endif
                            </div>
                             <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Status Baca Surat</label>
                                <select name="statusBaca" class="form-control">
                                    <option disabled>-- pilih Status Baca Surat --</option>
                                    <option value="sudah">Sudah</option>
                                    <option value="belum">Belum</option>
                                </select>
                                @if ($errors->has('statusBaca'))
                                    <small class="form-text text-danger">{{ $errors->first('statusBaca') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <hr style="width: 50%" class="mt-0">
                            <a href="{{ route('admin.disposisi_surat') }}" class="btn btn-warning btn-sm" style="color: white"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                            <button type="reset" name="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.tags-selector').select2();
        })
    </script>
@endpush
