<nav class="navbar has-shadow" >
    <div class="container is-fluid" >
        <div class="navbar-start">
            <a class="navbar-item" href="{{ route('casa.index') }}">

            </a>

        </div>
        <div class="navbar-end">

             <a href="{{route('logout')}}" class="navbar-item" onclick="event.preventDefault();
             document.getElementById('logout-form').submit();">
                <span class="icon">
                  <i class="fa fa-fw fa-sign-out m-r-5"></i>
                </span>
                Logout
              </a>
                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <a href="{{route('redirect.index') }}" class="navbar-item is-tab is-hidden-mobile">Meniu principal</a>
        </div>
    </div>
</nav>
