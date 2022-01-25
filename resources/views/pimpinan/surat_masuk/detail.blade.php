@extends('layouts.layout')
@section('title', 'Manajemen Surat Masuk')
@section('login_as','Pimpinan')
@section('user-login')
    @if (Auth::check())
    {{ Auth::user()->namaUser }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::check())
        {{ Auth::user()->namaUser }} ({{ Auth::user()->jabatan->namaJabatan }})
    @endif
@endsection
@section('sidebar-menu')
    @include('pimpinan/sidebar')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <section class="panel" style="margin-bottom:20px;">
                <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
                    <i class="fa fa-info-circle"></i>&nbsp;Informasi Detail Surat Masuk Dari <b><u>{{ $surat->pengirimSurat }}</u></b>
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
                            @endif
                        </div>
                        <div class="col-md-12">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Jenis Surat</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->jenisSurat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Pengirim Surat</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->pengirimSurat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Surat</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->nomorSurat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>perihal</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->perihal }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tujuan</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->pengirimSurat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lampiran</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->lampiran }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->catatan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sifat Surat</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->sifatSurat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Surat</th>
                                        <td> : </td>
                                        <td>
                                            {{ $surat->tanggalSurat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status Teruskan</th>
                                        <td> : </td>
                                        <td>
                                            @if ($surat->statusTeruskan == "sudah")
                                                <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Sudah Diteruskan</label>
                                                @else
                                                <label class="badge badge-danger"><i class="fa fa-minus-circle"></i>&nbsp; Belum Diteruskan</label>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status Baca</th>
                                        <td> : </td>
                                        <td>
                                            @if ($surat->statusBaca == "sudah")
                                                <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Sudah Diteruskan</label>
                                                @else
                                                <label class="badge badge-danger"><i class="fa fa-minus-circle"></i>&nbsp; Belum Diteruskan</label>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-6">
            <section class="panel" style="margin-bottom:20px;">
                <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
                    <i class="fa fa-history"></i>&nbsp;Riwayat Penerusan Surat Dari <b><u>{{ $surat->pengirimSurat }}</u></b>
                </header>
                <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
                    <div class="row" style="margin-right:-15px; margin-left:-15px;">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th>Penerus Surat : </th>
                                        <td>{{ $surat->namaPenerus }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diteruskan Ke : </th>
                                        <td>{{ $kepsek->namaUser }} (Kepala Sekolah)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#table').DataTable({
                responsive : true,
            });
        } );
    </script>
@endpush
