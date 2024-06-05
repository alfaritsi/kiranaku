<?php

/**
 * @application  : ESS Payslip - View
 * @author       : Akhmad Syaiful Yamang
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

$this->load->view('header')
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-success">
                    <form id="form-payslip-password">
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong><?php echo $title ?></strong></h3>
                        </div>
                        <div class="box-body">
                            <div class="callout callout-info">
                                <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
                                <ul style="margin: 0; padding-left: 1em;">
                                    <li>Password harus terdiri dari 6-10 karakter, dengan ketentuan harus berisi huruf besar, huruf kecil, dan angka.</li>
                                    <li>Jika lupa Password silahkan email ke <a href="mailto:epayslip.support@kiranamegatara.com"><b><u>epayslip.support@kiranamegatara.com</u></b></a></li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 form-horizontal">
                                    <?php if ($new === false) : ?>
                                        <div class="form-group has-feedback">
                                            <label for="pabrik" class="col-sm-4 control-label text-left">Password Lama</label>
                                            <div class="col-sm-8">
                                                <div class="input-group pass-with-button">
                                                    <input type="password" class="form-control" name="old_pass" required="required" placeholder="Password Lama">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn pass-btn" title="Show"><i class="fa fa-eye text-muted"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group has-feedback">
                                        <label for="pabrik" class="col-sm-4 control-label text-left">Password Baru</label>
                                        <div class="col-sm-8">
                                            <div class="input-group pass-with-button">
                                                <input type="password" class="form-control" name="new_pass" id="new_pass" required="required" placeholder="Password Baru">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn pass-btn" title="Show"><i class="fa fa-eye text-muted"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="pabrik" class="col-sm-4 control-label text-left">Konfirmasi Password Baru</label>
                                        <div class="col-sm-8">
                                            <div class="input-group pass-with-button">
                                                <input type="password" class="form-control" name="new_pass_conf" id="new_pass_conf" required="required" placeholder="Konfirmasi Password Baru">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn pass-btn" title="Show"><i class="fa fa-eye text-muted"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/ess/payslip/change.js"></script>