<li>
    <a href=" {{ route('admin.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li>
    <a href="{{ route('admin.jabatan') }}"><i class="fa fa-cog"></i>Manajemen Jabatan</a>
</li>

<li>
    <a href=" "><i class="fa fa-cog"></i>Manajemen Surat Masuk</a>
</li>

<li><a><i class="fa fa-list-alt"></i>Klasifikasi Surat <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href="">Surat Masuk</a></li>
        <li><a href="">Surat Keluar</a></li>
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