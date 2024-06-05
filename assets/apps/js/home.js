$(document).ready(function () {
	$(".tableTree").treetable({expandable: true});
	$(document).on("click", ".tableTree .branch-wrapper", function () {
		var indenter = $(this).closest("td").find(".indenter a");
		indenter.click();
	});


    $("#carousel-kirana-dna").on("slide.bs.carousel", function(ev) { 
    	var lazy;     
    	lazy = $(ev.relatedTarget).find("img[data-src]");     
    	lazy.attr("src", lazy.data('src'));     
    	lazy.removeAttr("data-src");   
    });


	$(document).on("click", ".tableTree .indenter", function () {
		var indenter = $("a", this);
		if (indenter.attr("title") == "Collapse") {
			indenter.removeClass("fa-plus-circle");
			indenter.addClass("fa-minus-circle");
		} else {
			indenter.removeClass("fa-minus-circle");
			indenter.addClass("fa-plus-circle");
		}
	});

	// $.ajax({
		// url: baseURL + 'home/get/news',
		// type: 'POST',
		// dataType: 'JSON',
		// success: function (data) {
			// if (data) {
				// var output = "";
				// var baseWeb = "https://www.kiranamegatara.com/";
				// $.each(data, function (i, v) {
					// checkImage(baseWeb + 'uploads/blog/' + v.image1, ".imgNews"+i);
					// output += '<li class="item">';
					// output += ' <div class="product-img">';
					// output += '     <img class="imgNews'+i+'" alt="Product Image">';
					// output += ' </div>';
					// output += ' <div class="product-info">';
					// output += '     <a href="' + baseWeb + 'blog/get/' + v.alias.toLowerCase() + '" class="product-title" target="_blank">' + v.blog + '</a>';
					// output += '     <span class="product-description">';
					// output += '         <i class="fa fa-calendar"> ' + generateDateFormat(v.publish_date) + '</i>';
					// output += '     </span>';
					// output += ' </div>';
					// output += '</li>';
				// });

				// $("#news-wrapper ul").html(output);
			// }
		// }
	// });

	// $.ajax({
		// url: baseURL + 'home/get/gallery',
		// type: 'POST',
		// dataType: 'JSON',
		// success: function (data) {
			// if (data) {
				// var output_gallery = "";
				// var baseWeb = "https://www.kiranamegatara.com/";
				// $.each(data, function (i, v) {
					// output_gallery += '<a href="javascript:void(0)"';
					// output_gallery += '    data-title="' + v.blog + '"';
					// output_gallery += '    data-image="' + baseWeb + 'uploads/blog/' + v.image1 + '"';
					// output_gallery += '    class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:10px">';
					// output_gallery += '    <div style="height: 70px; background: url(' + baseWeb + 'uploads/blog/' + v.image1 + ') no-repeat; background-size: 100% 100%;"></div>';
					// output_gallery += '</a>';
				// });

				// $("#gallery-wrapper .row").html(output_gallery);
			// }
		// }
	// });

	$(document).on("click", "#gallery-wrapper .row a", function () {
		$("#modal-gallery .modal-title").html($(this).data("title"));
		checkImage($(this).data("image"), $("#modal-gallery .modal-body .img-responsive"));
		// $("#modal-gallery .modal-body .img-responsive").attr("src", $(this).data("image"));
		$('#modal-gallery').modal('show');
	});

    Mycalendar();
});

//milad
/* initialize the calendar
 -----------------------------------------------------------------*/
function Mycalendar() {
    //Date for the calendar events (dummy data)
    $('#calendar').fullCalendar('destroy');

    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        buttonText: {//This is to add icons to the visible buttons
            prev: "<span class='fa fa-caret-left'></span>",
            next: "<span class='fa fa-caret-right'></span>",
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
        },
        // // // //Random default events
        // // // events: {
            // // // url: baseURL + 'home/get/milad',
            // // // method: 'POST',
            // // // failure: function () {
                // // // alert('there was an error while fetching events!');
            // // // },
            // // // color: '#0073b7',   // a non-ajax option
            // // // borderColor: '#0073b7'
        // // // },
		
        eventSources: [{
            //Random default events
            events: function (start, end, callback) {
				// console.log(events);
				// console.log(test);
                // console.log(generateDateFormat(start));
                // console.log(generateDateFormat(end));
				var current = $('#calendar').fullCalendar("getDate");
				var month = $('#calendar').fullCalendar("getDate").getMonth() + 1;
				var firstDate = new Date(current.getFullYear(), current.getMonth(), 1);
				firstDate = regenerateDatetimeFormat(firstDate,"YYYY-MM-DD","YYYY-MM-DD");
				var lastDate = new Date(current.getFullYear(), month, 0);
				lastDate = regenerateDatetimeFormat(lastDate,"YYYY-MM-DD","YYYY-MM-DD");
                $.ajax({
                    url: baseURL + 'home/get/milad',
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        start: firstDate,
                        end: lastDate
                    },
                    success: function(doc) {
						// console.log(doc);
                        // var event = [];
                        // $(doc).find('event').each(function() {
                        //     console.log(event);
                        //     // events.push({
                        //     //     title: $(this).attr('title'),
                        //     //     start: $(this).attr('start') // will be parsed
                        //     // });
                        // });
						
						// return events;
                        callback(doc);
                    }
                });
                // url: baseURL + 'home/get/milad',
                // method: 'POST',
                // failure: function () {
                    // alert('there was an error while fetching events!');
                // },
                // success: function(doc) {
                  // console.log(start.unix());
                  // console.log(end.unix());
                // },
                // color: '#0073b7',   // a non-ajax option
                // borderColor: '#0073b7'
            },
            color: '#0073b7',   // a non-ajax option
            borderColor: '#0073b7'
        }],
        //event click event calendar
        eventClick: function (event) {
            //detail
            var det = "<div class='row'><div class='col-sm-12 text-center margin-bottom'><img src='" + event.gambar + "' class='img-thumbnail img-responsive iimage' /></div></div>";
            det += "<table class='table table-bordered'>";
            det += "<tr><td>NIK</td><td>" + event.nik + "</td></tr>";
            det += "<tr><td>Nama</td><td>" + event.nama + "</td></tr>";
            det += "<tr><td>Email</td><td>" + event.email + "</td></tr>";
            det += "<tr><td>Bagian</td><td>" + event.bagian + "</td></tr>";
            // det	+= 		"<tr><td>Bagian</td><td>"+event.gambar+"</td></tr>";
            det += "</table>";
            $("#modalBody").html(det);
            $('#fullCalModal').modal();
        },
        lang: 'en',
        eventLimit: true, // If you set a number it will hide the itens
        eventLimitText: "Something", // Default is `more` (or "more" in the lang you pick in the option)
        editable: false,
        droppable: false, // this allows things to be dropped onto the calendar !!!
        eventRender: function (event, eventElement) { //set icon to title
            if (event.imageurl) {
                //console.log(eventElement.find("span.fc-event-title"));
                eventElement.find("span.fc-event-title").prepend(" <img src='" + event.imageurl + "' width='20' height='20'> ");
            }
        },
        eventAfterAllRender: function () {
            adjustNotification();
        }
    });
}
