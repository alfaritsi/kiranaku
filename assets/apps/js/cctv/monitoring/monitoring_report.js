/*
    @application  : Monitoring CCTV 
    @author       : Airiza Yuddha (7849)
    @contributor  :
        1. <airiza yuddha> (7849) 07.02.2019
         change function checkpersen dan checkpersendetail monitoring
        2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
        etc.
*/
$(document).ready(function () {
  
  // reload 
    $("#btn-new").on("click", function(e){
      location.reload();
      e.preventDefault();
    return false;
    });
  // set default show data
    var years = new Date().getFullYear();
    $("#filtertahun").val(years);
    get_datas(null,years);
  // date pitcker
    $('#filtertahun').datepicker({
        startView: 'year',
        minViewMode: "years",
        format: 'yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    }).on("changeDate", function () {
        var tahun = $("#filtertahun").val();
        get_datas(null,tahun);
    });   

});

function get_datas(pabrik=null,tahun=null){
  var x = 1;  
    $.ajax({
        url: baseURL+'cctv/monitoring/get/data_report_achv',
        type: 'POST',
        dataType: 'JSON',
        data: {
            
            pabrik  : pabrik,
            tahun   : tahun
            
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function(data){
          $("#div_mainTable").html("");
            $('#divOut').html("");
            $("#div_mainTable").append(""
                            +'<table class="table table-bordered table-striped " id="table_main" >'
                            +'<thead>'
                            +'    <th width=150>Pabrik / Week</th>'
                            +'    <th class="text-center">Januari</th>'
                            +'    <th class="text-center">Februari</th>'
                            +'    <th class="text-center">Maret</th> '                              
                            +'    <th class="text-center">April</th>'
                            +'    <th class="text-center">Mei</th>'
                            +'    <th class="text-center">Juni</th>'
                            +'    <th class="text-center">Juli</th>'
                            +'    <th class="text-center">Agustus</th>'
                            +'    <th class="text-center">September</th>'
                            +'    <th class="text-center">Oktober</th> '                              
                            +'    <th class="text-center">November</th>'
                            +'    <th class="text-center">Desember</th>'                            
                            // +'    <th class="text-center">Action</th>'
                            +'</thead>'
                            +'<tbody id="divOut">'
                            );
                            console.log(data);
                            $.each(data, function(iyear,dataYear){
                              //cek val 
                              // console.log(Object.keys(dataYear).length); // cek length 
                              if(jQuery.isEmptyObject(dataYear) == true){
                                $("#divOut").append("<tr data-tt-id='"+iyear+"'><td colspan=13>No data available in table</td></tr>");
                              } else {
                                $("#divOut").append("<tr data-tt-id='"+iyear+"'><td colspan=13>"+iyear+"</td></tr>");
                              }
                              $.each(dataYear , function(pabrik,pabrikData){
                                $("#divOut").append("<tr data-tt-id='"+iyear+"_"+pabrik+"' data-tt-parent-id='"+iyear+"' ><td>"+pabrik+"</td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_1' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_2' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_3' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_4' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_5' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_6' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_7' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_8' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_9' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_10' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_11' align='right'></td>"
                                              +"<td class='"+iyear+"_"+pabrik+"_12' align='right'></td>"
                                              +"</tr>");
                                for(var i=0; i<5; i++){
                                  $("#divOut").append("<tr data-tt-id='"+iyear+"_"+pabrik+"_"+i+"' data-tt-parent-id='"+iyear+"_"+pabrik+"'><td>Week "+(i+1)+"</td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_1_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_2_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_3_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_4_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_5_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_6_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_7_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_8_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_9_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_10_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_11_"+i+"' align='right'></td>"
                                                +"<td class='"+iyear+"_"+pabrik+"_12_"+i+"' align='right'></td>"
                                                +"</tr>");
                                }
                                console.log(pabrikData);
                                $.each(pabrikData, function(i, v){
                                  if(i == "sum"){
                                    $.each(v, function(key, val){
                                      var class_td  = tahun+"_"+pabrik+"_"+key;
                                      // var valuesum  = 0;
                                      // if(val.toFixed(2) <=100) valuesum = val.toFixed(2);
                                      // else valuesum = "100.00";
                                      var val_sum   = checkpersen(val.toFixed(2),class_td);
                                      $("."+tahun+"_"+pabrik+"_"+key).html(val_sum);
                                    });
                                  }
                                  if(i == "trans"){
                                    $.each(v, function(key, val){
                                      $.each(val, function(idx, value){
                                        var class_td    = tahun+"_"+pabrik+"_"+key+"_"+(parseInt(value.week)-1);
                                        var valdetail  = checkpersen_detail(value.persen,class_td);
                                        $("."+tahun+"_"+pabrik+"_"+key+"_"+(parseInt(value.week)-1) ).html(valdetail);
                                      });
                                    });
                                  }
                                });
                              });
                            });

                                  
            $("#div_mainTable").append(""
                            +'</tbody>'
                            +'</table>');
                            
            $('#table_main').treetable({ expandable: true });       
           
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();
        }
    });
}

function checkpersen(value,td,type){
  var datapersen = value;
  var label = "";
  // console.log(datapersen);
  $.ajax({
    url: baseURL+'cctv/master/get/mcriteria',
        type: 'POST',
        dataType: 'JSON',
        data: {
            
            persen    : datapersen
            
        },
        success: function(data){
          $.each(data, function(i,v){
            var splitcss = v.css.split('-');
            splitcss = 'alert alert-'+splitcss[1];
            // total += parseFloat(vv.persen);
            // label = "<span class='"+v.css+"'>"+datapersen+" % &nbsp;&nbsp;&nbsp;</span>";
            label = " <span class='"+splitcss+"' style='padding:1.5px; width: 60px; display: inline-block; margin-bottom: 0px; '>"+datapersen+" % </span>";
            $("."+td).html(label);
            
          })
          
          
        },
        error: function(jqXHR,error, errorThrown) {  
          if(jqXHR.status&&jqXHR.status==400){
              console.log(jqXHR.responseText); 
          }else{
            console.log("Something went wrong");
            label = " <span class='alert alert-danger' style='padding:1.5px; width: 60px; display: inline-block; margin-bottom: 0px;'>0 % </span>";
            $("."+td).html(label);
          }
        } 
  });
  // e.preventDefault();
  return false;
}

function checkpersen_detail(value,td,type){
  var datapersen = value;
  var label = "";
  // console.log(datapersen);
  $.ajax({
    url: baseURL+'cctv/master/get/mcriteria',
        type: 'POST',
        dataType: 'JSON',
        data: {
            
            persen    : datapersen
            
        },
        success: function(data){
          $.each(data, function(i,v){
            // total += parseFloat(vv.persen);
            //set color font by criteria
            var splitcss = v.css.split('-');
            splitcss = 'text-'+splitcss[1];
            if(Number(datapersen) == 0 ){
              datapersen = "0.00";
            } else {
              datapersen = datapersen;
            }
            label = " <span class='"+splitcss+"' style='padding:.5rem'>"+datapersen+" % </span> ";
            $("."+td).html(label);
            
          })
        },
        error: function(jqXHR,error, errorThrown) {  
          if(jqXHR.status&&jqXHR.status==400){
              console.log(jqXHR.responseText); 
          }else{
            console.log("Something went wrong");
            label = " <span class='text-danger' style='padding:.5rem'>0 % </span> ";
            // $("td[data-"+type+"='"+td+"']").html(label);
            $("."+td).html(label);
          }
        }
  });
  // e.preventDefault();
  return false;
}
