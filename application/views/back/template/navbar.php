<header class="main-header">
  <!-- Logo -->
  <a href="<?php echo base_url('dashboard') ?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>A</b>ZM</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><img src="<?php echo base_url('assets/images/company/'.$company_data->company_photo_thumb) ?>" alt="Company Logo"> <?php echo $company_data->company_name ?></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-gear"></i>
          </a>
          <ul class="dropdown-menu">
            <li class="user-body">
              <div class="row">
                <div class="col-xs-6 text-center">
                  <a href="<?php echo base_url('auth/update_profile/'.$this->session->id_users) ?>">Update Profile</a>
                </div>
                <div class="col-xs-6 text-center">
                  <a href="<?php echo base_url('auth/change_password') ?>">Change Password</a>
                </div>
              </div>
              <!-- /.row -->
            </li>
            <li class="user-footer">
              <div class="pull-right">
                <a href="<?php echo base_url('auth/logout') ?>" class="btn btn-default btn-flat">Logout</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
