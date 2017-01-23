
<?php $this->load->view('admin/common/sidebar') ?>

        <!-- top navigation -->
       <?php $this->load->view('admin/common/navbar') ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Patients <small>List of all patients</small></h3>
              </div>

            </div>

            <div class="clearfix"></div>

            <div class="row">
              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <?php
                $info_message = $this->session->flashdata('info_message');
                $success_message = $this->session->flashdata('success_message');
                $error_message = $this->session->flashdata('error_message');
                if (!empty($info_message)) 
                {
                  ?>
                  <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong><?php echo $info_message; ?></strong>
                  </div>
                  <?php
                } 
                elseif (!empty($success_message)) 
                {?>
              <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong><?php echo $success_message; ?></strong>
                  </div>
                <?php
                }
                elseif (!empty($error_message)) 
                { ?>
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong><?php echo $error_message; ?></strong>
                  </div>
                <?php
                }
                 ?>
                  <div class="x_title">
                    <h2>Patients Record <small></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Test name</th>
                          <th>Test Patient</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <?php if (!empty($tests)) {
                        $i = 0;
                        foreach ($tests as $value) { ?>
                        <tr>
                          <td><?php echo $value->test_name; ?></td>
                          <td><?php 
                          foreach ($patient_name as $names) 
                          {
                            if ($names->test_id == $value->test_id) 
                            {
                              echo $names->patient_firstname.' '.$names->patient_lastname;
                            }
                          } ?></td>
                          <td>
                            <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal<?php echo $i; ?>"><i class="fa fa-folder"></i> View </a>
                            <a href="<?php echo site_url('admin/edit_tests/'.$value->test_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                            <a href="<?php echo site_url('admin/delete_tests/'.$value->test_id); ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                          </td>
                          <?php 

                          $i++;
                            }
                          } ?>
                        </tr>
                      </tbody>
                    </table>

                    <?php 
                    if (!empty($tests)) {
                      $j = 0;
                      foreach ($tests as $value) { ?>
                      <div id="myModal<?php echo $j; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title"><?php echo $value->test_name; ?></h4>
                            </div>
                            <div class="modal-body">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Test Content Name</th>
                                  <th>Normal Value</th>
                                  <th>Unit Value</th>
                                  <th>Result</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($tests_fields_patient as $fields) {
                                if ($fields->test_id == $value->test_id) {?>
                                <tr>
                                  <td><?php echo $fields->field_name; ?></td>
                                  <td><?php echo $fields->field_normal_value; ?></td>
                                  <td><?php echo $fields->field_unit; ?></td>
                                  <td><?php echo $fields->field_result; ?></td>
                                </tr>
                                <?php 
                                } 
                              } 
                          $j++;
                              ?>
                              </tbody>
                            </table>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>

                        </div>
                      </div>
                      <?php 
                        }
                      } ?>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/vendors/jquery/dist/jquery.min.js'); ?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('assets/vendors/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url('assets/vendors/fastclick/lib/fastclick.js'); ?>"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url('assets/vendors/nprogress/nprogress.js'); ?>"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url('assets/vendors/iCheck/icheck.min.js'); ?>"></script>
    <!-- Datatables -->
    <script src="<?php echo base_url('assets/vendors/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-buttons/js/buttons.flash.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-buttons/js/buttons.print.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/datatables.net-scroller/js/datatables.scroller.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/jszip/dist/jszip.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/pdfmake/build/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/pdfmake/build/vfs_fonts.js'); ?>"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url(); ?>assets/build/js/custom.min.js"></script>

    <!-- Datatables -->
    <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 1, 'asc' ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
    </script>
    <!-- /Datatables -->
  </body>
</html>