@php
    use App\Models\DisposisiSurat;
@endphp
@extends('layouts.layout')
@section('title', 'Manajemen Surat Masuk')
@section('login_as')
    {{ Auth::user()->jabatan->namaJabatan }}
@endsection
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
    @include('pimpinan/sidebar')
@endsection
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Sistem Informasi Manajemen Surat MAN Insan Cendikia Bengkulu Tengah
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                            <strong>Berhasil :</strong>{{ $message }}
                        </div>
                        @elseif ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>Gagal :</strong>{{ $message }}
                            </div>
                            @else
                            <div class="alert alert-primary alert-block" id="keterangan">
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Semua surat masuk terurut berdasarkan waktu upload, surat masuk yang muncul hanya surat masuk yang sudah diteruskan ke pimpinan saja, silahkan lakukan disposisi surat masuk jika diperlukan
                            </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <a href="{{ route('pimpinan.surat_masuk.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp; Tambah Surat Masuk</a>
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengirim Surat</th>
                                <th>Nomor Surat</th>
                                <th>Perihal</th>
                                <th>Lampiran</th>
                                <th>Status Disposisi</th>
                                <th>Status Baca</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($surat_masuks as $surat)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td style="width: 20%">
                                        <a href="">{{ $surat->pengirimSurat }}</a>
                                        <hr style="margin: 5px 0px !important">
                                        <label class="badge badge-info">{{ $surat->jenisSurat }}</label>
                                        <label style="text-transform: capitalize" class="badge badge-primary">{{ $surat->sifatSurat }}</label>
                                        <label class="badge badge-success">{{ $surat->tanggalSurat }}</label> <br>
                                        Diinput pada {{ $surat->created_at ? $surat->created_at->diffForHumans() : '-' }}
                                    </td>
                                    <td> {{ $surat->nomorSurat }} </td>
                                    <td> {{ $surat->perihal }} </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{ asset('upload/surat_masuk/'.\Illuminate\Support\Str::slug(Auth::user()->namaUser).'/'.$surat->lampiran) }}" download="{{ $surat->lampiran }}"><i class="fa fa-download"></i>&nbsp; Download</a>
                                        {{-- <a href="" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp; Download</a> --}}
                                    </td>
                                    <td>
                                        @php
                                            $disposisi = count(DisposisiSurat::where('suratMasukId',$surat->id)->where('pengirimId',Auth::user()->id)->get());
                                        @endphp
                                        @if ($disposisi > 0)
                                            <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Sudah Didisposisikan</label>
                                            <hr style="padding: 0px;">
                                            <button class="btn btn-primary btn-sm" disabled><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</button>
                                            @else
                                            <label class="badge badge-danger"><i class="fa fa-minus-circle"></i>&nbsp; Belum Didisposisikan</label>
                                            <hr style="padding: 0px;">
                                            <a onclick="disposisikan({{ $surat->id }})" class="btn btn-primary btn-sm" style="color: white; cursor: pointer;"><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($surat->statusBaca == "sudah")
                                            <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Sudah Dibaca</label>
                                            @else
                                            <label class="badge badge-danger"><i class="fa fa-minus-circle"></i>&nbsp; Belum Dibaca</label>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pimpinan.surat_masuk.detail',[$surat->id]) }}" style="color: white; cursor: pointer;" class="btn btn-info btn-sm"><i class="fa fa-info-circle"></i>&nbsp; Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Konfirmasi Teruskan-->
                    <div class="modal fade" id="modalKonfirmasiDisposisi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('pimpinan.surat_masuk.disposisikan') }}" method="POST">
                                {{ csrf_field() }} {{ method_field("PATCH") }}
                                <div class="modal-header">
                                <p class="modal-title" id="exampleModalLabel">Konfirmasi Disposisi Surat</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                   <div class="row">
                                       <div class="col-md-12">
                                            <p>
                                                Apakah Anda Yakin Akan Mendisposisikan Surat Masuk?
                                                Jika iya, silahkan pilih pimpinan yang akan menerima disposisi surat !
                                            </p>
                                       </div>
                                       <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Pilih Pimpinan Yang Akan Menerima Disposisi</label>
                                                <select name="penerimaId" class="form-control" id="">
                                                    <option disabled selected>-- pilih pimpinan --</option>
                                                    @foreach ($pimpinans as $pimpinan)
                                                        <option value="{{ $pimpinan->id }}">{{ $pimpinan->namaUser }} - {{ $pimpinan->namaJabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                       </div>
                                   </div>
                                    
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;Ya, Teruskan</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#table').DataTable({
                responsive : true,
            });
        } );

        function disposisikan(id){
            $('#modalKonfirmasiDisposisi').modal('show');
            $('#disposisiId').val(id);
        }
    </script>
@endpush
