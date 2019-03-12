<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <section class="content-header" style="padding-bottom: 40px;">
    <h1 class="pull-left">
      Tambah Leasing
    </h1>
    <div class="pull-right">
    <a href="<?php echo base_url('master/leasing')?>" class="btn btn-primary pull-right"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
  </section>

  <section class="content">
        <!-- Default box -->
        <div class="box">
        <div class="box-header with-border">
        </div>
        <div class="box-body">
            <form action="<?php echo base_url('master/leasing/simpan')?>" method="post" class="form-horizontal">
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">Nama Leasing</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" value="<?php ?>" class="form-control" name="txt_nm_cust" required>
                </div>
            </div>
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">Alamat Lengkap</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" placeholder="Alamat 1" value="<?php ?>" class="form-control" name="txt_alamat1" required>
                </div>
            </div>
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label"></label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" placeholder="Alamat 2" value="<?php ?>" class="form-control" name="txt_alamat2">
                </div>
            </div>
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">Kota</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" value="<?php ?>" class="form-control" name="txt_kota" required>
                </div>
            </div>
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">Kode Pos</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" value="<?php ?>" class="form-control" name="txt_kdpos" required>
                </div>
            </div>
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">No Telepon</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" value="<?php ?>" class="form-control" name="txt_telepon" required>
                </div>
            </div>   
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">Pekerjaan</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" value="<?php ?>" class="form-control" name="txt_pekerjaan" required>
                </div>
            </div>  
            <div class="form-group ">
                <label for="dari" class="col-md-3 control-label">UP</label>
                <div class="col-md-7 col-sm-12 required">
                    <input type="text" value="<?php ?>" class="form-control" name="txt_up" required>
                </div>
            </div> 
            <div class="box-footer text-right">
                <a class="btn btn-default" href="<?php echo base_url('master/leasing/tambah')?>"><i class="fa fa-close"></i> Batal</a>
                <button type="submit" name="btnSimpan" class="btn btn-success"><i class="fa fa-check icon-white"></i> Simpan</button>
             </div>
            </form>
        </div>
        </div>
    <!-- /.box -->
    </section>
 </div>
<!-- /.content-wrapper-->
