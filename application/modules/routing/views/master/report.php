<!--
/*
@application  : Email Routing
@author       : Matthew Jodi
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>

<div class="content-wrapper">
	<section class="content">
    <div class="row">
      <div class="col-sm-8">
    		<div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><strong>Manage Report</strong></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-bordered table-striped my-datatable-extends-order">
              <thead>
                <th>Report</th>
                <th>Apps</th>
                <th>Period</th>
                <th>Function</th>
                <th>Editorial</th>
              	<th>Action</th>
              </thead>
              <tbody>
              	<?php
              	 foreach ($report as $dt) {

                  if($dt->report_code == null || $dt->report_code == ' '){
                    $code = "unknown";
                  }else{
                    $code = $dt->report_code;
                  }
                 
              		echo "<tr>";
                  echo "<td>[".$code." ] - ".$dt->report_name."<br>".$dt->label_active."</td>";
                  echo "<td>".$dt->report_app."</td>";
                  echo "<td>".$dt->report_type."</td>";
                  echo "<td>".$dt->report_function."</td>";
                  echo "<td>".$dt->editorial."</td>";
              		echo "<td>
                          <div class='input-group-btn'>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
                            <ul class='dropdown-menu pull-right'>";
                      if($dt->is_active == '1'){ 
                        echo "<li><a href='#' class='edit' data-edit='".$dt->id_report."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                              <li><a href='#' class='delete' data-delete='".$dt->id_report."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                      }
                      if($dt->is_active == '0'){
                        echo "<li><a href='#' class='set_active-report' data-activate='".$dt->id_report."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
              <h3 class="box-title title-form">Buat Report Baru</h3>
              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Report Baru</button>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form role="form" class="form-report">
            <div class="box-body">
              <div class="form-group">
<!--                 <label for="id_report">Report ID</label>
                <input type="text" class="form-control" name="id_report" id="id_report" placeholder="Masukkkan Report ID"> -->

                
                <label for="report_code">Report Code</label>
                <input type="text" class="form-control" name="report_code" id="report_code" placeholder="Masukkkan Report Code">
                
                <label for="report_name">Report Name</label>
                <input type="text" class="form-control" name="report_name" id="report_name" placeholder="Masukkkan Report Name">
                
                <label for="report_app">Report App</label>
                <select class="form-control select2" name="report_app" id="report_app" style="width: 100%;" required="required">
                  <option value="0">Silahkan pilih</option>
                  <option value="Kiranalytics">Kiranalytics</option>
                  <option value="SMS">SMS</option>
                </select>
                
                <label for="report_type">Period</label>
                <select class="form-control select2" name="report_type" id="report_type" style="width: 100%;" required="required">
                  <option value="0">Silahkan pilih</option>
                  <option value="Daily">Daily</option>
                  <option value="Weekly">Weekly</option>
                  <option value="Monthly">Monthly</option>
                </select>

                <label for="report_function">Report Function</label>
                <input type="text" class="form-control" name="report_function" id="report_function" placeholder="Masukkkan Report Function">

                <label for="editorial">Editorial</label>
                <textarea class="form-control" name="editorial" id="editorial"></textarea>

                <label for="footnote">Footnote</label>
                <textarea class="form-control" name="footnote" id="footnote"></textarea>

                <label for="requestor">Requestor</label>
                <select class="form-control select2-user-search" name="requestor" id="requestor" style="width: 100%;" ></select>

                <label for="requestor">Exclude Nama</label>
                <select class="form-control" multiple="multiple" name="exclude_nik[]" id="exclude_nik"></select>
				

              </div>
            </div>
            <div class="box-footer">
              <input type="hidden" name="id_report"  id="id_report">
			  
              <button type="button" value="submit" name="action_btn" class="btn btn-success">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
	</section>
</div>

<?php $this->load->view('footer') ?>

<script src="<?php echo base_url() ?>assets/apps/js/routing/report.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>