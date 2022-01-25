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
                                        @if (Auth::user()->jabatan->namaJabatan == "Kepala Sekolah")
                                            <a href="{{ route('pimpinan.surat_masuk.baca_surat',[$surat->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Baca Surat</a>
                                        @else
                                            <a href="{{ route('pimpinan.surat_masuk.baca_surat2',[$surat->disposisiId]) }}" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Baca Surat</a>
                                        @endif
                                        {{-- <a href="" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp; Download</a> --}}
                                    </td>
                                    <td>
                                        @if (Auth::user()->jabatan->namaJabatan == "Kepala Sekolah")
                                            @if ($surat->isDisposisi != null)
                                                    @if ($surat->isDisposisi == '1')
                                                        <label class="badge badge-primary"><i class="fa fa-check-circle"></i>&nbsp; Sudah Didisposisikan</label>
                                                        <hr style="padding: 0px;">
                                                        <button class="btn btn-primary btn-sm" disabled><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</button>
                                                        @else
                                                        <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Proses Diakhiri</label>
                                                        <hr style="padding: 0px;">
                                                        <button class="btn btn-primary btn-sm" disabled><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</button>
                                                    @endif
                                                @else
                                                <label class="badge badge-danger"><i class="fa fa-minus-circle"></i>&nbsp; Belum Didisposisikan</label>
                                                <hr style="padding: 0px;">
                                                @if (Auth::user()->jabatan->namaJabatan == "Kepala Sekolah")
                                                    <a onclick="disposisikan({{ $surat->id }})" class="btn btn-primary btn-sm" style="color: white; cursor: pointer;"><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</a>
                                                @else
                                                    <a onclick="disposisikan2({{ $surat->disposisiId }})" class="btn btn-primary btn-sm" style="color: white; cursor: pointer;"><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</a>
                                                @endif
                                            @endif
                                        @else
                                        @if ($surat->statusDisposisi == 'belum')
                                                    <label class="badge badge-primary"><i class="fa fa-check-circle"></i>&nbsp; Belum Didisposisikan</label>
                                                    <hr style="padding: 0px;">
                                                    <button class="btn btn-primary btn-sm" disabled><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</button>
                                            @else
                                            <label class="badge badge-danger"><i class="fa fa-minus-circle"></i>&nbsp; Belum Didisposisikan</label>
                                            <hr style="padding: 0px;">
                                            @if (Auth::user()->jabatan->namaJabatan == "Kepala Sekolah")
                                                <a onclick="disposisikan({{ $surat->id }})" class="btn btn-primary btn-sm" style="color: white; cursor: pointer;"><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</a>
                                            @else
                                                <a onclick="disposisikan2({{ $surat->disposisiId }})" class="btn btn-primary btn-sm" style="color: white; cursor: pointer;"><i class="fa fa-arrow-right"></i>&nbsp; Disposisikan Surat</a>
                                            @endif
                                        @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($surat->statusBaca == "sudah" && $surat->isDisposisi == '1')
                                            <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Sudah Dibaca</label>
                                            @elseif ($surat->statusBaca == "belum" && $surat->isDisposisi == '1')
                                            <label class="badge badge-warning"><i class="fa fa-clock-o"></i>&nbsp; Menunggu Dibaca</label>
                                            {{-- @elseif ($surat->statusBaca == "belum" && $surat->isDisposisi == "0")
                                            <label class="badge badge-warning"><i class="fa fa-clock-o"></i>&nbsp; Belum Didisposisikan</label> --}}
                                            @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pimpinan.surat_masuk.detail',[$surat->id]) }}" style="color: white; cursor: pointer;" class="btn btn-info btn-sm"><i class="fa fa-info-circle"></i>&nbsp; Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Konfirmasi Disposisi-->
                    <div class="modal fade" id="modalKonfirmasiDisposisi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('pimpinan.surat_masuk.disposisikan') }}" method="POST" id="myForm">
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
                                            <div class="alert alert-primary">
                                                Apakah Anda Yakin Akan Mendisposisikan Surat Masuk? <br>
                                                Jika iya, silahkan pilih iya dan pilih pimpinan yang akan menerima disposisi surat ! <br>
                                                Jika tidak, maka silahkan pilih selesai dan proses disposisi surat akan selesai
                                            </div>
                                       </div>
                                       <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Pilih iya jika ingin mendisposisikan surat</label>
                                                <select name="disposisi" id="disposisi" class="form-control">
                                                    <option disabled selected>-- pilih status disposisi --</option>
                                                    <option value="ya">Ya, Disposisikan</option>
                                                    <option value="tidak">Tidak, Proses Selesai</option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="form-disposisi" style="display: none">
                                                <label for="">Pilih Pimpinan Yang Akan Menerima Disposisi</label>
                                                <input type="hidden" name="suratId" id="suratId">
                                                <select name="penerimaId" class="form-control" id="penerimaId">
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
                                    <button type="submit" id="submit" disabled class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;Ya, Teruskan</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Konfirmasi Disposisi2-->
            <div class="modal fade" id="modalKonfirmasiDisposisi2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('pimpinan.surat_masuk.disposisikan2') }}" method="POST" id="myForm">
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
                                    <div class="alert alert-primary">
                                        Apakah Anda Yakin Akan Mendisposisikan Surat Masuk? <br>
                                        Jika iya, silahkan pilih iya dan pilih pimpinan yang akan menerima disposisi surat ! <br>
                                        Jika tidak, maka silahkan pilih selesai dan proses disposisi surat akan selesai
                                    </div>
                               </div>
                               <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Pilih iya jika ingin mendisposisikan surat</label>
                                        <select name="disposisi" id="disposisi2" class="form-control">
                                            <option disabled selected>-- pilih status disposisi --</option>
                                            <option value="ya">Ya, Disposisikan</option>
                                            <option value="tidak">Tidak, Proses Selesai</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="form-disposisi2" style="display: none">
                                        <label for="">Pilih Pimpinan Yang Akan Menerima Disposisi</label>
                                        <input type="hidden" name="disposisiId" id="disposisiId">
                                        <select name="penerimaId" class="form-control" id="penerimaId">
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
                            <button type="submit" id="submit2" disabled class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;Ya, Teruskan</button>
                        </div>
                    </form>
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
            $('#suratId').val(id);
        }

        function disposisikan2(disposisiId){
            $('#modalKonfirmasiDisposisi2').modal('show');
            $('#disposisiId').val(disposisiId);
        }

        $('#disposisi').change(function(){
            status = $('#disposisi').val();
            if (status == "ya") {
                $('#form-disposisi').show();
                $("#submit").prop('disabled',false);
            }else{
                $('#form-disposisi').hide();
                $("#submit").prop('disabled',false);
                $('#penerimaId').val("");
            }
        });

        $('#disposisi2').change(function(){
            status = $('#disposisi2').val();
            if (status == "ya") {
                $('#form-disposisi2').show();
                $("#submit2").prop('disabled',false);
            }else{
                $('#form-disposisi2').hide();
                $("#submit2").prop('disabled',false);
                $('#penerimaId2').val("");
            }
        });
    </script>
@endpush
