@php
    use App\Models\KlasifikasiBerkas;
@endphp
@extends('layouts.layout')
@section('title', 'Manajemen Data Personal')
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
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Sistem Informasi Pengelolaan Surat Masuk dan Keluar MAN IC Bengkulu Tengah
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
                            {{--  <div class="alert alert-success alert-block" id="keterangan">
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut semua berkas berkas yang sudah diupload oleh operator !!
                            </div>  --}}
                    @endif
                </div>
                <div class="col-md-12">
                    <a href="{{ route('admin.user.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                </div>
                  
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jabatan</th>
                                <th>NIP</th>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Hak Akses</th>
                                <th>Status</th>
                                <th>Ubah Status</th>
                            
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $user->namaJabatan }}</td>
                                <td>{{ $user->nip }}</td>
                                <td>{{ $user->namaUser }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->telephone }}</td>
                                <td>{{ $user->hakAkses }}</td>
                                 <td>
                                       @if ($user->status == "aktif")
                                           <label class="badge badge-primary">Aktif</label>
                                           @else
                                           <label class="badge badge-danger">Tidak Aktif</label>
                                       @endif
                                   </td>
                            
                                 <td>
                                    @if ($user->status== "aktif")
                                        <form action="{{ route('admin.user.nonAktifkanStatus', [$user->id]) }}" method="POST">
                                            {{ csrf_field() }} {{ method_field('PATCH') }}
                                            <button type="submit" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-thumbs-down"></i></button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.user.aktifkanStatus', [$user->id]) }}" method="POST">
                                            {{ csrf_field() }} {{ method_field('PATCH') }}
                                            <button type="submit" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-thumbs-up"></i></button>
                                        </form>
                                    @endif
                                   </td>
                                   <td>
                                    <a href="{{ route('admin.user.edit',[$user->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>&nbsp; Edit</a>
                                    <form action="{{ route('admin.user.delete',[$user->id]) }}" method="POST">
                                        {{ csrf_field() }} {{ method_field("DELETE") }}

                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp; Hapus</button>
                                    </form>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Hapus-->
                    <div class="modal fade modal-danger" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                {{--  <form action="{{ route('admin.surat_masuk.delete',[$user->id]) }}"method="POST">
                                    {{ csrf_field() }} {{ method_field('DELETE') }}  --}}
                                    <div class="modal-header">
                                        <p style="font-size:15px; font-weight:bold;" class="modal-title"><i class="fa fa-trash"></i>&nbsp;Form Konfirmasi Hapus Data</p>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="id" id="id_hapus">
                                                Apakah anda yakin ingin menghapus data? klik hapus jika iya !!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" style="border: 1px solid #fff;background: transparent;color: #fff;" class="btn btn-sm btn-outline pull-left" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                        <button type="submit" style="border: 1px solid #fff;background: transparent;color: #fff;" class="btn btn-sm btn-outline"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus</button>
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
    </script>
@endpush
