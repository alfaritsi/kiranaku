<!--
/*
@application  : PERMISI
@author       : Akhmad Syaiful Yamang (8347)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
  .datepicker{
    border-radius: 0;
  }
</style>

<div class="content-wrapper">
	<section class="content">
    <div class="row">
      <div class="col-sm-8">
    		<div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><strong>Manage Topic</strong></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-bordered table-striped my-datatable-extends-order">
              <thead>
                <th>Topic</th>
                <th>Apps</th>
                <th>Period</th>
                <th>Start Date</th>
                <th>Last Send</th>
              	<th>Action</th>
              </thead>
              <tbody>
              	<?php
                 foreach ($topic as $dt) {

                  if($dt->topic_code == null || $dt->topic_code == ' '){
                    $code = "unknown";
                  }else{
                    $code = $dt->topic_code;
                  }
                 
                  echo "<tr>";
                  echo "<td>[".$code."] - ".$dt->topic."<br>".$dt->label_active."</td>";
                  echo "<td>".$dt->topic_app."</td>";
                  echo "<td>".$dt->periode."</td>";
                  echo "<td>".$dt->start_date."</td>";
                  echo "<td>".$dt->last_send_log."</td>";
                  echo "<td>
                          <div class='input-group-btn'>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
                            <ul class='dropdown-menu pull-right'>";
                      if($dt->is_active == '1'){ 
                        echo "<li><a href='#' class='edit' data-edit='".$dt->id_topic."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                              <li><a href='#' class='delete' data-delete='".$dt->id_topic."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                      }
                      if($dt->is_active == '0'){
                        echo "<li><a href='#' class='set_active-topic' data-activate='".$dt->id_topic."'><i class='fa fa-check'></i> Set Aktif</a></li>";
                      }
                  echo "    </ul>
                          </div>
                        </td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
      </div> <!-- col 8-->
      <div class="col-sm-4">
        <div class="box box-success">
          <div class="box-header with-border">
              <h3 class="box-title title-form">Buat Topic Baru</h3>
              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Topic Baru</button>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form role="form" class="form-topic">
            <div class="box-body">
              <div class="form-group">
                <label for="topic_code">Topic Code</label>
                <input type="text" class="form-control" name="topic_code" id="topic_code" placeholder="Masukkkan Topic Code">
                
                <label for="topic">Topic Name</label>
                <input type="text" class="form-control" name="topic" id="topic" placeholder="Masukkkan Topic Name">
                
                <label for="topic_app">Topic App</label>
                <select class="form-control select2" name="topic_app" id="topic_app" style="width: 100%;" required="required">
                  <option value="0">Silahkan pilih</option>
                  <option value="Kiranalytics">Kiranalytics</option>
                  <option value="SMS">SMS</option>
                </select>
                
                <div class="row">
                <div class="col-md-6">
                <label for="periode">Periode</label>
                  <select class="form-control select2" name="periode" id="periode" style="width: 100%;" required="required">
                    <option value="0">Silahkan pilih periode</option>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                  </select>
                </div>
                <div class="col-md-6">
                <label for="frekuensi">Frekuensi</label>
                  <input type="number" class="form-control angka" name="frekuensi" id="frekuensi" placeholder="0">
                </div>
                </div>

                <label for="start_date">Starting Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control datepicker" id="start_date" name="start_date" required="required">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>

                <label for="last_send">Last Send</label>
                <input type="text" class="form-control" name="last_send_log" id="last_send_log" placeholder="Tanggal Terakhir dikirim" readonly="readonly">

                <label for="select_report">Select Report</label>
                <select class="form-control select2" multiple="multiple" name="select_report[]" id="select_report" style="width: 100%;" required="required">
                </select>

              </div>
            </div>
            <div class="box-footer">
              <input type="hidden" name="id_topic">
              <button type="button" value="submit" name="action_btn" class="btn btn-success">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
	</section>
</div>

<?php $this->load->view('footer') ?>

<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/routing/topic.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>