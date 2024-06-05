/*
@application  : Auth Control JS
@author       : Octe Reviyanto Nugroho
@contributor    :
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function () {
    $('#role').select2({
        allowClear:true
    });
    $('#role').on('change',function(){
        $('form').submit();
    });
});