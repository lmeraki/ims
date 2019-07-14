<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'System') }} | Dashboard</title>

  <link rel="stylesheet" type="text/css" href="/css/app.css">

</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user"></i> My account
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- <span class="dropdown-item dropdown-header">Hello, </span> -->
          <div class="dropdown-divider"></div>
          <div class="dropdown-item">
            <i class="fa fa-user"></i> {{ Auth::user()->name }}
          </div> 
         <!--  <a href="#" class="dropdown-item dropdown-footer">
            
          </a> -->
          <div class="dropdown-divider"></div>
          <router-link to="account_settings" class="dropdown-item">
            <i class="fa fa-cog"></i> Account Settings
          </router-link>
          <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                <i class="fa fa-power-off"></i>
                {{ __('Logout') }}
              </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
      <img src="/logo/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">IMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/user_images/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            @if(Auth::user()->user_type == "admin")
              <li class="nav-item">
                <router-link to="admin_dashboard" class="nav-link">
                  <i class="nav-icon fa fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="admin_category" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right"></i>
                  <p>
                    Categories
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="admin_itemslist" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right"></i>
                  <p>
                    Items
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="admin_stockin" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right"></i>
                  <p>
                    Stock in
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>              

              <li class="nav-item">
                <router-link to="admin_pending" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right"></i>
                  <p>
                    Pending Items
                    <!-- <span class="right badge badge-danger"></span> -->
                  </p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="admin_userslist" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right"></i>
                  <p>
                    Users
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>
            @else

              <li class="nav-item">
                <router-link to="user_dashboard" class="nav-link">
                  <i class="nav-icon fa fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link to="user_itemslist" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right"></i>
                  <p>
                    Borrow Items
                    <!-- <span class="right badge badge-danger">New</span> -->
                  </p>
                </router-link>
              </li>

            @endif

          

       <!--    <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fa fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
            </ul>
          </li> -->
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <div class="content-header"> -->
      <!-- <div class="container-fluid"> -->
        <!-- <div class="row mb-2"> -->
          <!-- <div class="col-sm-6"> -->
            <!-- <h1 class="m-0 text-dark">Dashboard</h1> -->
          <!-- </div>/.col -->
          <!-- <div class="col-sm-6"> -->
            <!-- <ol class="breadcrumb float-sm-right"> -->
              <!-- <li class="breadcrumb-item"><a href="/home">Home</a></li> -->
              <!-- <li class="breadcrumb-item active">Dashboard</li> -->
            <!-- </ol> -->
          <!-- </div>/.col -->
        <!-- </div>/.row -->
      <!-- </div>/.container-fluid -->
    <!-- </div> -->
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid mt-5">
        <!-- Small boxes (Stat box) -->

        <router-view></router-view>
        <!-- set progress bar -->
        <vue-progress-bar></vue-progress-bar>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y");?>.</strong>
    All rights reserved.
    
  </footer>

</div>
<!-- ./wrapper -->
<script type="text/javascript" src="/js/app.js"></script>
</body>
</html>
