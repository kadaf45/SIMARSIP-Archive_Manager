<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <?php if($this->session->photo_thumb == NULL){ ?>
          <img src="<?php echo base_url('assets/images/noimage.jpg') ?>" class="img-circle" alt="No Image Found">
        <?php } else{ ?>
          <img src="<?php echo base_url('assets/images/user/'.$this->session->photo_thumb) ?>" class="img-circle" alt="<?php echo $this->session->name ?>">
        <?php } ?>
      </div>
      <div class="pull-left info">
        <p><?php echo $this->session->name ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN MENU</li>
      <?php
      $this->db->join('menu_access', 'menu.id_menu = menu_access.menu_id');
      $this->db->join('submenu', 'menu.id_menu = submenu.id_submenu', 'LEFT');
      $this->db->where('menu_access.usertype_id', $this->session->usertype);
      $this->db->where('menu.is_active', '1');
      $this->db->group_by('menu.id_menu');
      $this->db->order_by('menu.order_no');
      $menu = $this->db->get('menu')->result();
      ?>

      <?php foreach($menu as $m){ ?>
        <!-- jika menu tidak punya submenu -->
        <?php if($m->submenu_id == NULL){ ?>
          <?php if(current_url() == base_url().$m->menu_url){$active = 'class="active"';}else{$active = '';} ?>
          <li <?php echo $active ?>> <a href="<?php echo base_url().$m->menu_url ?>">
            <i class="fa <?php echo $m->menu_icon ?>"></i> <span><?php echo $m->menu_name ?></span> </a>
          </li>
        <?php }
        else
        {
          $this->db->join('menu', 'submenu.id_submenu = menu.id_menu', 'LEFT');
          $this->db->join('menu_access', 'submenu.id_submenu = menu_access.submenu_id');
          $this->db->where('submenu.menu_id', $m->id_menu);
          $this->db->where('menu_access.usertype_id', $this->session->usertype);
          $this->db->order_by('submenu.order_no');
          $submenu = $this->db->get('submenu')->result();

          if($this->uri->segment(1) == $m->menu_slug){$actives = 'class="active treeview menu-open"';}
          else{$actives = 'class="treeview"';}
        ?>
          <li <?php echo $actives ?>>
            <a href="#">
              <i class="fa <?php echo $m->menu_icon ?>"></i> <span><?php echo $m->menu_name ?></span>
              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
              <?php foreach($submenu as $sm){ ?>
                <?php if(current_url() == base_url().$sm->submenu_url){$active = 'class="active"';}else{$active = '';} ?>
                <li <?php echo $active ?>><a href="<?php echo base_url().$sm->submenu_url ?>"><i class="fa fa-circle-o"></i> <?php echo $sm->submenu_name ?></a> </li>
              <?php } ?>
            </ul>
          </li>
      <?php }} ?>

      <li class="header">SETTINGS</li>
      <li class="<?php if($this->uri->segment(1) == 'auth' && $this->uri->segment(2) == 'log_list'){echo "active";} ?>"><a href="<?php echo base_url('auth/log_list') ?>"><i class="fa fa-clock-o"></i> <span>Log System Process</span></a></li>
      <li class="<?php if($this->uri->segment(1) == 'company'){echo "active";} ?>" ><a href="<?php echo base_url('company/update/1') ?>"><i class="fa fa-building"></i> <span>Company Profile</span></a></li>
      <?php if(is_superadmin()){ ?>
        <li class="<?php if($this->uri->segment(1) == 'auth' && $this->uri->segment(2) != 'log_list'){echo "active";} ?> treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>User Management</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == 'auth' && $this->uri->segment(2) == 'create'){echo 'class="active"';} ?>><a href="<?php echo base_url('auth/create') ?>"><i class="fa fa-circle-o"></i> Add User</a></li>
            <li <?php if($this->uri->segment(1) == 'auth' && $this->uri->segment(2) == ''){echo 'class="active"';} ?>><a href="<?php echo base_url('auth') ?>"><i class="fa fa-circle-o"></i> User List</a></li>
            <li <?php if($this->uri->segment(1) == 'auth' && $this->uri->segment(2) == 'deleted_list'){echo 'class="active"';} ?>><a href="<?php echo base_url('auth/deleted_list') ?>"><i class="fa fa-circle-o"></i> Recycle Bin</a></li>
          </ul>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'usertype'){echo "active";} ?> treeview">
          <a href="#">
            <i class="fa fa-legal"></i> <span>Usertype Management</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == 'usertype' && $this->uri->segment(2) == 'create'){echo 'class="active"';} ?>><a href="<?php echo base_url('usertype/create') ?>"><i class="fa fa-circle-o"></i> Add Usertype</a></li>
            <li <?php if($this->uri->segment(1) == 'usertype' && $this->uri->segment(2) == ''){echo 'class="active"';} ?>><a href="<?php echo base_url('usertype') ?>"><i class="fa fa-circle-o"></i> Usertype List</a></li>
          </ul>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'menu'){echo "active";} ?> treeview">
          <a href="#">
            <i class="fa fa-list"></i> <span>Menu Management</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == 'menu' && $this->uri->segment(2) == 'create'){echo 'class="active"';} ?>><a href="<?php echo base_url('menu/create') ?>"><i class="fa fa-circle-o"></i> Add Menu</a></li>
            <li <?php if($this->uri->segment(1) == 'menu' && $this->uri->segment(2) == ''){echo 'class="active"';} ?>><a href="<?php echo base_url('menu') ?>"><i class="fa fa-circle-o"></i> Menu List</a></li>
          </ul>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'submenu'){echo "active";} ?> treeview">
          <a href="#">
            <i class="fa fa-list"></i> <span>SubMenu Management</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == 'submenu' && $this->uri->segment(2) == 'create'){echo 'class="active"';} ?>><a href="<?php echo base_url('submenu/create') ?>"><i class="fa fa-circle-o"></i> Add SubMenu</a></li>
            <li <?php if($this->uri->segment(1) == 'submenu' && $this->uri->segment(2) == ''){echo 'class="active"';} ?>><a href="<?php echo base_url('submenu') ?>"><i class="fa fa-circle-o"></i> SubMenu List</a></li>
          </ul>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'menuaccess'){echo "active";} ?> treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Menu Access Management</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == 'menuaccess' && $this->uri->segment(2) == 'create'){echo 'class="active"';} ?>><a href="<?php echo base_url('menuaccess/create') ?>"><i class="fa fa-circle-o"></i> Add Menu Access</a></li>
            <li <?php if($this->uri->segment(1) == 'menuaccess' && $this->uri->segment(2) == ''){echo 'class="active"';} ?>><a href="<?php echo base_url('menuaccess') ?>"><i class="fa fa-circle-o"></i> Menu Access List</a></li>
          </ul>
        </li>
        <li class="<?php if($this->uri->segment(1) == 'template'){echo "active";} ?> treeview">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Template Management</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($this->uri->segment(1) == 'template' && $this->uri->segment(3) == '1'){echo 'class="active"';} ?>><a href="<?php echo base_url('template/update/1') ?>"><i class="fa fa-circle-o"></i> Layout</a></li>
            <li <?php if($this->uri->segment(1) == 'template' && $this->uri->segment(3) == '2'){echo 'class="active"';} ?>><a href="<?php echo base_url('template/update/2') ?>"><i class="fa fa-circle-o"></i> Skins</a></li>
          </ul>
        </li>
      <?php } ?>
      <li><a href="<?php echo base_url('auth/logout') ?>"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
