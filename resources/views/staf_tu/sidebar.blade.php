<li>
    <a href=" {{ route('staf_tu.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li>
    <a href="{{ route('staf_tu.surat_masuk') }}"><i class="fa fa-envelope-square"></i>Manajemen Surat Masuk</a>
</li>

<li>
    <a href="{{ route('staf_tu.surat_keluar') }}"><i class="fa fa-envelope"></i>Manajemen Surat Keluar</a>
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