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
                    <form action="{{ route('admin.surat_keluar.post') }}" enctype="multipart/form-data" method="POST">
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
                                <label for="exampleInputEmail1">Penerima Surat</label>
                                <input type="text" name="penerima"  class="tags form-control @error('penerima') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('penerima'))
                                        <small class="form-text text-danger">{{ $errors->first('penerima') }}</small>
                                    @endif
                                </div>
                            </div>
                                <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nomor Surat</label>
                                <input type="text" name="nomorSurat"  class="tags form-control @error('nomorSurat') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('nomorSurat'))
                                        <small class="form-text text-danger">{{ $errors->first('nomorSurat') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Perihal</label>
                                <input type="text" name="perihal"  class="tags form-control @error('perihal') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('perihal'))
                                        <small class="form-text text-danger">{{ $errors->first('perihal') }}</small>
                                    @endif
                                </div>
                            </div>
                          <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Tujuan</label>
                                <input type="text" name="tujuan"  class="tags form-control @error('tujuan') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('tujuan'))
                                        <small class="form-text text-danger">{{ $errors->first('tujuan') }}</small>
                                    @endif
                                </div>
                            </div>
                             <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Upload Surat Keluar : <a class="text-danger">Harap masukan file DOC/PDF. Max : 2MB</a></label>
                                <input type="file" name="lampiran" id="lampiran" class="form-control @error('lampiran') is-invalid @enderror" style="padding-bottom:30px;">
                                @if ($errors->has('lampiran'))
                                    <small class="form-text text-danger">{{ $errors->first('lampiran') }}</small>
                                @endif
                            </div>
                             <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Catatan</label>
                                <input type="text" name="catatan"  class="tags form-control @error('catatan') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('catatan'))
                                        <small class="form-text text-danger">{{ $errors->first('catatan') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Sifat Surat</label>
                                <select name="sifatSurat" class="form-control">
                                    <option disabled>-- pilih Sifat Surat --</option>
                                    <option value="penting">Penting</option>
                                    <option value="segera">Segera</option>
                                    <option value="rahasia">Rahasia</option>
                                    <option value="biasa">Biasa</option>
                                 
                                </select>
                                @if ($errors->has('sifatSurat'))
                                    <small class="form-text text-danger">{{ $errors->first('sifatSurat') }}</small>
                                @endif
                            </div>
                           <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Tanggal Surat</label>
                                <input type="date" name="tanggalSurat"  class="tags form-control @error('tanggalSurat') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('tanggalSurat'))
                                        <small class="form-text text-danger">{{ $errors->first('tanggalSurat') }}</small>
                                    @endif
                                </div>
                            </div>
                            
                           {{--  <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Status Teruskan Surat</label>
                                <select name="statusTeruskan" class="form-control">
                                    <option disabled>-- pilih Status Teruskan Surat --</option>
                                    <option value="sudah">Sudah</option>
                                    <option value="belum">Belum</option>
                                </select>
                                @if ($errors->has('statusTeruskan'))
                                    <small class="form-text text-danger">{{ $errors->first('statusTeruskan') }}</small>
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
                            </div>  --}}
                        </div>
                        <div class="col-md-12 text-center">
                            <hr style="width: 50%" class="mt-0">
                            <a href="{{ route('admin.surat_keluar') }}" class="btn btn-warning btn-sm" style="color: white"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
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
