<nav class="navbar has-shadow" >
                <div class="container is-fluid" >
                    <div class="navbar-start">
                        <a class="navbar-item" href="{{ route('casa.index') }}">
                           {{ucfirst ($user['magazin'])}}
                        </a>
                        <a href="{{route('casa.set_tab', ['tab' => 'Tab1']) }}" class="navbar-item is-tab is-hidden-mobile m-l-10">1</a>
                        <a href="{{route('casa.set_tab', ['tab' => 'Tab2']) }}" class="navbar-item is-tab is-hidden-mobile">2</a>
                        <a href="{{route('casa.set_tab', ['tab' => 'Tab3']) }}" class="navbar-item is-tab is-hidden-mobile">3</a>
                        <span class="navbar-item">||</span>
                        <a href="{{route('casa.set_tab', ['tab' => 'Tab4']) }}" class="navbar-item is-tab is-hidden-mobile">4</a>
                        <a href="{{route('casa.set_tab', ['tab' => 'Tab5']) }}" class="navbar-item is-tab is-hidden-mobile">5</a>
                        <a href="{{route('casa.set_tab', ['tab' => 'Tab6']) }}" class="navbar-item is-tab is-hidden-mobile">6</a>

                        <a href="{{route('casa.index') }}"  class="navbar-item is-tab is-hidden-mobile mr-l-30">Produse</a>
                        <a href="#" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Tag</a>
                        <a href="#" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Resetare</a>
                        <a href="#" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Utile</a>
                        <a href="{{route('garantii.index')}}" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Garantii</a>
                        <a href="{{route('garantii.intrate')}}" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Garantiile mele</a>
                        <a href="{{route('stoc.index')}}" id="button-navbar" class="navbar-item is-tab is-hidden-mobile">Stocuri</a>

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
