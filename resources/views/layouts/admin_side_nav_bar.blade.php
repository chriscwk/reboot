<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ action('AdminController@index') }}">
              	<span class="menu-title">Dashboard</span>
              	<i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ action('AdminController@categories') }}">
              	<span class="menu-title">Categories</span>
              	<i class="mdi mdi-apps menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#articles-menu" data-toggle="collapse">
              	<span class="menu-title">Articles</span>
                <i class="menu-arrow"></i>
              	<i class="mdi mdi-book menu-icon"></i>
            </a>
            <div class="collapse" id="articles-menu" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ action('AdminController@articles') }}">All Articles</a></li>
                </ul>
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ action('AdminController@edited_articles') }}">Edited Articles</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#users-menu" data-toggle="collapse">
              	<span class="menu-title">Users</span>
                <i class="menu-arrow"></i>
              	<i class="mdi mdi-account menu-icon"></i>
            </a>
            <div class="collapse" id="users-menu" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ action('AdminController@publishers') }}">Publishers</a></li>
                </ul>
            </div>
        </li>
	</ul>
</nav>