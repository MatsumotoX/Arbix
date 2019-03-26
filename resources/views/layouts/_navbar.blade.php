<header class="cd-main-header">
    <a href="/" class="cd-logo"><img src="/img/svg/CXA_Logo.svg" alt="Logo" onmouseover="this.src='/img/svg/CXA_Logo_White.svg'"
                                      onmouseleave="this.src='/img/svg/CXA_Logo.svg'"></a>

    <div class="cd-search is-hidden">
        @yield('searchfield', View::make('layouts._searchfield'))
    </div> <!-- cd-search -->

    <a href="#0" class="cd-nav-trigger"><span></span></a>

    <nav class="cd-nav">
        <ul class="cd-top-nav">
            <li><a href="#0">Support</a></li>
            <li class="has-children account">
                <a href="#0">
                    @auth
                        <img src="{{ Auth::user()->avatar }}" alt="avatar">
                        {{ Auth::user()->name }}
                    @endauth

                    @guest
                        <img src="/assets/images/eecl-logo-1-274x183.png" alt="avatar">
                        Login
                    @endguest
                </a>

                <ul>

                    <li><a href="#0">My Account</a></li>
                    <li><a href="#0">Edit Account</a></li>
                    <li>
                        <a href=""
                           onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header> <!-- .cd-main-header -->