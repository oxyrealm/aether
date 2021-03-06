<div class="wrap">
    <h2 class="nav-tab-wrapper">
        @foreach($tabs as $tab)
            <a href="?page={{ \Oxyrealm\Aether\Admin::SLUG . '_settings' }}&tab={{ $tab['id'] }}" class="nav-tab {{ $selected_tab === $tab['id'] ? 'nav-tab-active' : '' }}">{{ $tab['label'] }} </a>
        @endforeach
    </h2>

    @foreach ($contents as $content)
        @php
            call_user_func_array( $content['callback'], $content['args']?? [] );
        @endphp
    @endforeach
</div>


