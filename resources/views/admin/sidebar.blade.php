<li>
    <a href=" {{ route('admin.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li>
    <a href="{{ route('admin.jabatan') }}"><i class="fa fa-user"></i>Manajemen Jabatan</a>
</li>
<li>
    <a href=" {{ route('admin.jenis_surat') }}"><i class="fa fa-book"></i>Manajemen Jenis Surat</a>
</li>
<li><a><i class="fa fa-envelope"></i>Klasifikasi Surat <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('admin.surat_masuk') }}">Surat Masuk</a></li>
        <li><a href=" {{ route('admin.surat_keluar') }}">Surat Keluar</a></li>
    </ul>
</li>
{{--  <li>
    <a href=" {{ route('admin.disposisi_surat') }}"><i class="fa fa-comments"></i>Manajemen Disposisi Surat</a>
</li>  --}}
{{--  <li>
    <a href=" {{ route('admin.user') }}"><i class="fa fa-users"></i>Manajemen User</a>
</li>  --}}
<li><a><i class="fa fa-users"></i>Manajemen User <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('admin.administrator') }}">Admin</a></li>
        <li><a href=" {{ route('admin.staf_tu') }}">Staf TU</a></li>
        <li><a href=" {{ route('admin.user') }}">Pimpinan</a></li>

    </ul>
</li>


<li style="padding-left:2px;">
    <a class="dropdown-item" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
        <i class="fa fa-power-off text-danger"></i>{{__('Logout') }}
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</li>

@push('styles')
    <style>
        .noclick       {
            pointer-events: none;
            cursor: context-menu;
            background-color: #ed5249;
        }

        .default{
            cursor: default;
        }

        .set_active{
            border-right: 5px solid #1ABB9C;
        }

    </style>
@endpush