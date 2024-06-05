<style type='text/javascript'>
function pageScroll() {
        window.scrollBy(0,50); // horizontal and vertical scroll increments
        scrolldelay = setTimeout('pageScroll()',100); // scrolls every 100 milliseconds
}
</style>
<div class="modal fade" role="dialog" id="modal-komentar">
	<!--<div class="modal-dialog modal-sm" role="document">-->
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Komentar</h4>
                <p class="no-margin" id="title-spk"></p>
            </div>
            <div class="modal-body no-padding direct-chat-warning">
                <div class="direct-chat-messages" style="min-height: 300px;" id="chat-body">
                    <div class="direct-chat-msg hide template-left">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">User</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            -
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
                            -
                        </div>
                        <!-- /.direct-chat-text -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="form-komentar">
                    <div class="input-group">
                        <input type="hidden" id="id_spk" name="id_spk">
                        <!--<input type="text" name="komentar" placeholder="Type Message ..." class="form-control" maxlength="150">-->
						<textarea class="form-control" name="komentar" id="komentar" placeholder="Type Message ..." style="margin: 0px; width: 492px; height: 71px;" maxlength="255"></textarea>	   
                        <span class="input-group-btn">
                            <button name="btn_komentar" type="button" class="btn btn-warning btn-flat">Send</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>