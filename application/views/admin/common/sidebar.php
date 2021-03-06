<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Pathology Lab!</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile">
      <div class="profile_pic">
        <img src="<?php echo base_url('assets/images/img.jpg'); ?>" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2>John Doe</h2>
      </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-home"></i> Patients <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo site_url('admin/add_patients'); ?>">Add Patients</a></li>
              <li><a href="<?php echo site_url('admin/manage_patients'); ?>">Manage Patients</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-edit"></i> Reports <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo site_url('admin/add_report'); ?>">Add Report</a></li>
              <li><a href="<?php echo site_url('admin/manage_reports'); ?>">Manage Report</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-desktop"></i> Tests <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo site_url('admin/add_test'); ?>">Add Patient Tests</a></li>
              <li><a href="<?php echo site_url('admin/manage_tests'); ?>">Manage Patient Tests</a></li>
            </ul>
          </li>
          
        </ul>
      </div>

    </div>
    <!-- /sidebar menu -->

  </div>
</div>
