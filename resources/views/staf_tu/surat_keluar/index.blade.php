@extends('layouts.layout')
@section('title', 'Manajemen Surat Keluar')
@section('login_as', 'Staf Tata Usaha')
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
    @include('staf_tu/sidebar')
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
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Semua surat keluar terurut berdasarkan waktu upload, silahkan klik tombol detail untuk informasi lengkap mengenai surat keluar !!
                            </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <a href="{{ route('staf_tu.surat_keluar.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp; Tambah Surat Keluar</a>
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengirim Surat</th>
                                <th>Nomor Surat</th>
                                <th>Perihal</th>
                                <th>Ditujukan Kepada</th>
                                <th>Lampiran</th>
                                <th>Tanggal Surat</th>

                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($surat_keluars as $surat)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td style="width: 20%">
                                        <a href="">{{ $surat->penerima }}</a>
                                        <hr style="margin: 5px 0px !important">
                                        <label class="badge badge-info">{{ $surat->jenisSurat }}</label>
                                        <label style="text-transform: capitalize" class="badge badge-primary">{{ $surat->sifatSurat }}</label>
                                        <label class="badge badge-success">{{ $surat->tanggalSurat }}</label> <br>
                                        Diinput pada {{ $surat->created_at ? $surat->created_at->diffForHumans() : '-' }}
                                    </td>
                                    <td> {{ $surat->nomorSurat }} </td>
                                    <td> {{ $surat->perihal }} </td>
                                    <td> {{ $surat->tujuan }} </td>
                                    <td> {{ $surat->tanggalSurat }} </td>
                                    
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{ asset('upload/surat_keluar/'.\Illuminate\Support\Str::slug(Auth::user()->namaUser).'/'.$surat->lampiran) }}" download="{{ $surat->lampiran }}"><i class="fa fa-download"></i>&nbsp; Download</a>
                                        {{-- <a href="" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp; Download</a> --}}
                                    </td>
                                    <td>
                                        <a onclick="detail({{ $surat->id }})" style="color: white; cursor: pointer;" class="btn btn-info btn-sm"><i class="fa fa-info-circle"></i>&nbsp; Detail</a>
                                       <a href="{{ route('staf_tu.surat_keluar.edit',[$surat->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>&nbsp; Edit</a>
                                    <form action="{{ route('staf_tu.surat_keluar.delete',[$surat->id]) }}" method="POST">
                                        {{ csrf_field() }} {{ method_field("DELETE") }}

                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp; Hapus</button>
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Konfirmasi Teruskan-->
                    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            
                            <div class="modal-header">
                            <p class="modal-title" id="exampleModalLabel"><i class="fa fa-info-circle"></i>&nbsp;Informasi Detail Surat Keluar Untuk <b><u><a id="judulDetail"></u></b></a></p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-primary">
                                            Informasi detail surat keluar
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>Jenis Surat</th>
                                                    <td>:</td>
                                                    <td id="jenisSurat"></td>
                                                </tr>
                                                <tr>
                                                    <th>Penerima Surat</th>
                                                    <td>:</td>
                                                    <td id="penerima"></td>
                                                </tr>
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>:</td>
                                                    <td id="nomorSurat"></td>
                                                </tr>
                                                <tr>
                                                    <th>Perihal</th>
                                                    <td>:</td>
                                                    <td id="perihal"></td>
                                                </tr>
                                                <tr>
                                                    <th>Tujuan</th>
                                                    <td>:</td>
                                                    <td id="tujuan"></td>
                                                </tr>
                                                <tr>
                                                    <th>Lampiran</th>
                                                    <td>:</td>
                                                    <td id="lampiran"></td>
                                                </tr>
                                                <tr>
                                                    <th>Catatan</th>
                                                    <td>:</td>
                                                    <td id="catatan"></td>
                                                </tr>
                                                <tr>
                                                    <th>Sifat Surat</th>
                                                    <td>:</td>
                                                    <td id="sifatSurat"></td>
                                                </tr>
                                                <tr>
                                                    <th>tanggalSurat</th>
                                                    <td>:</td>
                                                    <td id="tanggalSurat"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;Ya, Teruskan</button>
                            </div>
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

        function detail(id){
            // alert(id);
            $.ajax({
                url: "{{ url('staf_tu/surat_keluar') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalDetail').modal('show');
                    $('#judulDetail').text(data['penerima'])
                    $('#jenisSurat').text(data['jenisSurat'])
                    $('#penerima').text(data['penerima'])
                    $('#nomorSurat').text(data['nomorSurat'])
                    $('#perihal').text(data['perihal'])
                    $('#tujuan').text(data['tujuan'])
                    $('#lampiran').text(data['lampiran'])
                    $('#catatan').text(data['catatan'])
                    $('#sifatSurat').text(data['sifatSurat'])
                    $('#tanggalSurat').text(data['tanggalSurat'])
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }
    </script>
@endpush
