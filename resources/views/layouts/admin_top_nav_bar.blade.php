<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  	<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    	<a class="navbar-brand brand-logo" href="{{ action('AdminController@index') }}">{{-- <img src="../../images/logo.svg" alt="logo"/> --}}Reboot</a>
    	<a class="navbar-brand brand-logo-mini" href="{{ action('AdminController@index') }}">{{-- <img src="../../images/logo-mini.svg" alt="logo"/> --}}RB</a>
  	</div>
  	<div class="navbar-menu-wrapper d-flex align-items-stretch">
        <ul class="navbar-nav navbar-nav-right">
          	<li class="nav-item nav-profile dropdown">
            	<a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
	              	<div class="nav-profile-img">
	                	<img src="http://icons-for-free.com/free-icons/png/512/1287507.png" alt="image">
	                	{{-- <span class="availability-status online"></span>              --}}
	              	</div>
	              	<div class="nav-profile-text">
	                	<p class="mb-1 text-black">Administrator</p>
	              	</div>
            	</a>
            	<div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
              		<a class="dropdown-item" href="{{ action('AdminController@admin_sign_out') }}">
                		<i class="mdi mdi-logout mr-2 text-primary"></i>
                		Signout
              		</a>
            	</div>
          	</li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
  	</div>
</nav>