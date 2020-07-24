<?php $this->load->view('back/template/meta'); ?>
<div class="wrapper">

  <?php $this->load->view('back/template/navbar'); ?>
  <?php $this->load->view('back/template/sidebar'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><?php echo $page_title ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><?php echo $module ?></li>
        <li class="active"><?php echo $page_title ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

      <div class="box box-primary">
        <div class="box-header"><a href="<?php echo $add_action ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $btn_add ?></a> </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="text-align: center">No</th>
                  <th style="text-align: center">Kode Arsip</th>
                  <th style="text-align: center">Nama Arsip</th>
                  <th style="text-align: center">Rak</th>
                  <th style="text-align: center">Baris</th>
                  <th style="text-align: center">Status</th>
                  <th style="text-align: center">File</th>
                  <th style="text-align: center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach($get_all_deleted as $data){
                  if($data->is_available == '0'){ $is_available = "<button class='btn btn-xs btn-success'><i class='fa fa-check'></i> ADA</button> ";}
                  else{ $is_available = "<button class='btn btn-xs btn-danger'><i class='fa fa-remove'></i> DIPINJAM</button>";}
                  // action
                  $restore = '<a href="'.base_url('arsip/restore/'.$data->id_arsip).'" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i></a>';
                  $delete = '<a href="'.base_url('arsip/delete_permanent/'.$data->id_arsip).'" onClick="return confirm(\'Are you sure?\');" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i></a>';
                ?>
                  <tr>
                    <td style="text-align: center"><?php echo $no++ ?></td>
                    <td style="text-align: left"><?php echo $data->kode_arsip ?></td>
                    <td style="text-align: center"><?php echo $data->arsip_name ?></td>
                    <td style="text-align: center"><?php echo $data->rak_name ?></td>
                    <td style="text-align: center"><?php echo $data->baris_name ?></td>
                    <td style="text-align: center"><?php echo $is_available ?></td>
                    <td style="text-align: center"><a href="<?php echo base_url('assets/file/arsip/'.$data->file_arsip) ?>" class="btn btn-xs btn-info"> DOWNLOAD</a></td>
                    <td style="text-align: center"><?php echo $restore ?> <?php echo $delete ?></td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th style="text-align: center">No</th>
                  <th style="text-align: center">Kode Arsip</th>
                  <th style="text-align: center">Nama Arsip</th>
                  <th style="text-align: center">Rak</th>
                  <th style="text-align: center">Baris</th>
                  <th style="text-align: center">Status</th>
                  <th style="text-align: center">File</th>
                  <th style="text-align: center">Action</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('back/template/footer'); ?>
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/') ?>datatables-bs/css/dataTables.bootstrap.min.css">
  <script src="<?php echo base_url('assets/plugins/') ?>datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url('assets/plugins/') ?>datatables-bs/js/dataTables.bootstrap.min.js"></script>
  <script>
  $(document).ready( function () {
    $('#dataTable').DataTable();
  } );
  </script>

</div>
<!-- ./wrapper -->

</body>
</html>
