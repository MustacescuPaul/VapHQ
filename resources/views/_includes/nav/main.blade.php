<nav class="navbar has-shadow">
                <div class="container is-fluid">
                    <div class="navbar-start">
                        <a class="navbar-item" href="{{ route('home') }}">
                           <img src="https://gist.github.com/fluidicon.png" alt="DevMarketer Logo"> 
                        </a>
                        <a href="#" class="navbar-item is-tab is-hidden-mobile m-l-10">1</a>
                        <a href="#" class="navbar-item is-tab is-hidden-mobile">2</a>
                        <a href="#" class="navbar-item is-tab is-hidden-mobile">3</a>
                        <span class="navbar-item">||</span>
                        <a href="#" class="navbar-item is-tab is-hidden-mobile">4</a>
                        <a href="#" class="navbar-item is-tab is-hidden-mobile">5</a>
                        <a href="#" class="navbar-item is-tab is-hidden-mobile">6</a>

                        <a href="#"  class="navbar-item is-tab is-hidden-mobile mr-l-30">Produse</a>
                        <a href="#" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Tag</a>
                        <a href="#" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Resetare</a>
                        <a href="#" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Utile</a>
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
                    </div>
                </div>
            </nav>