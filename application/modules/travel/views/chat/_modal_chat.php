<!-- for attchment -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>

<div class="modal fade" role="dialog" id="modal-chat-spd">
    <!--<div class="modal-dialog modal-sm" role="document">-->
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Chat</h4>
                <p class="no-margin" id="title-spk"></p>
            </div>
            <div class="modal-body no-padding direct-chat-warning">
                <div class="direct-chat-messages" style="min-height: 300px;" id="chat-body">
                    <div class="direct-chat-msg left hide template-left">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">User</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            <!-- <img class="lampiran img-responsive hide"/> -->
                            <a class="lampiran btn btn-default btn-flat btn-xs pull-right hide" data-fancybox="lampiran"><i class="fa fa-search"></i style="color:black;"> Lihat lampiran</a>
                            <span class="message"></span>
                        </div>
                        <!-- /.direct-chat-text -->
                    </div>
                    <div class="direct-chat-msg right hide template-right">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right">User</span>
                            <span class="direct-chat-timestamp pull-left">23 Jan 2:00 pm</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            <a class="lampiran btn btn-outline btn-primary btn-flat btn-xs pull-right hide" data-fancybox="lampiran"><i class="fa fa-search"></i> Lihat lampiran</a>
                            <p class="message"></p>
                        </div>
                        <!-- /.direst-chat-text -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="form-komentar">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-sm-9">
                            <!--<input type="text" name="komentar" placeholder="Type Message ..." class="form-control" maxlength="150">-->
                            <textarea class="form-control" name="comment" id="comment" placeholder="Type Message ..."
                                      maxlength="255" style="height: 100%;" rows="3"></textarea>
                        </div>
                        <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="btn-group btn-sm no-padding">
                                            <a class="btn btn-flat btn-default fileinput-exists fileinput-zoom"
                                               target="_blank" data-fancybox="gallery"><i class="fa fa-search"></i></a>
                                            <a class="btn btn-flat btn-facebook btn-file">
                                                <div class="fileinput-new"><i class="fa fa-plus"></i> Lampiran</div>
                                                <div class="fileinput-exists"><i class="fa fa-edit"></i></div>
                                                <input type="file" name="lampiran" id="lampiran_chat" autocomplete="off">
                                            </a>
                                            <a href="#" class="btn btn-flat btn-pinterest fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button name="btn_komentar" type="button"
                                            class="btn btn-warning btn-flat btn-block">Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-btn">


                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
