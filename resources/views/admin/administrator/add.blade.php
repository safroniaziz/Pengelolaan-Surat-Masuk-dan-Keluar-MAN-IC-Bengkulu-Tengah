@extends('layouts.layout')
@section('title', 'Manajemen Klasifikasi Berkas')
@section('login_as', 'Administrator')
@section('user-login')
    @if (Auth::check())
    {{ Auth::user()->namaUser }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::check())
    {{ Auth::user()->namaUser }}
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
                    <form action="{{ route('admin.administrator.post') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="col-md-12">
                        

                               <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jabatan</label>
                                <select name="jabatan" class="form-control" id="">
                                    <option disabled>-- pilih Jabatan  --</option>
                                    @foreach ($jabatan as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->namaJabatan }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('jabatan'))
                                    <small class="form-text text-danger">{{ $errors->first('jabatan') }}</small>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">NIP</label>
                                <input type="text" name="nip"  class="tags form-control @error('nip') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('nip'))
                                        <small class="form-text text-danger">{{ $errors->first('nip') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nama User</label>
                                <input type="text" name="namaUser"  class="tags form-control @error('namaUser') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('namaUser'))
                                        <small class="form-text text-danger">{{ $errors->first('namaUser') }}</small>
                                    @endif
                                </div>
                            </div>
                                  
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="text" name="email"  class="tags form-control @error('email') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('email'))
                                        <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Password</label>
                                <input type="text" name="password"  class="tags form-control @error('password') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('password'))
                                        <small class="form-text text-danger">{{ $errors->first('password') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nomor HP</label>
                                <input type="text" name="telephone"  class="tags form-control @error('telephone') is-invalid @enderror" />
                                <div>
                                    @if ($errors->has('telephone'))
                                        <small class="form-text text-danger">{{ $errors->first('telephone') }}</small>
                                    @endif
                                </div>
                            </div>
                             <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Hak Akses</label>
                                <select name="hakAkses" class="form-control">
                                    <option disabled>-- pilih Hak Akses --</option>
                                    <option value="admin">Admin</option>
                                   
                                </select>
                                @if ($errors->has('hakAkses'))
                                    <small class="form-text text-danger">{{ $errors->first('hakAkses') }}</small>
                                @endif
                            </div>
                         <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" class="form-control">
                                    <option disabled>-- pilih Status --</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                                @if ($errors->has('status'))
                                    <small class="form-text text-danger">{{ $errors->first('status') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <hr style="width: 50%" class="mt-0">
                            <a href="{{ route('admin.administrator') }}" class="btn btn-warning btn-sm" style="color: white"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
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
