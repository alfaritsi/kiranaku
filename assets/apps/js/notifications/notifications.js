let dataNotif = null;

$(document).ready(function () {

	$(document).on('click', '.more-info', function () {
		var title = $(this).data("title");
		let data = $(this).data("notif");
		// dataNotif = data;
		// console.log(dataNotif);

		$('#KiranaModals .modal-dialog').addClass("modal-sm-01");

		var output = "";
		output += '<div class="box-body" style="display: block;">';
		output += '    <table >';
		output += '        <tbody>';

		let dismiss = '';
		if (data.length == 1)
			dismiss = ' onclick="$(\'#KiranaModals\').modal(\'hide\')"';

		$.each(data, function (i, v) {
			output += '             <tr>';
			output += '                 <td>';
			output += '                     <a href="' + (v.url == '-' ? 'javascript:void(0)' : v.url) + '"';
			output += '                         target="'+(v.url == '-' ? '_self' : '_blank')+'" ' + dismiss;
			output += '                         data-toggle="tooltip"';
			output += '                         data-original-title="' + v.notification + '">';
			output += '                         <font color="black">';
			output += '                             <i class="fa fa-circle"></i> &nbsp;';
			output += '                             ' + v.notification;
			output += '                         </font>';
			output += '                     </a>';
			output += '                 </td>';
			output += '             </tr>';
		});

		output += '        </tbody>';
		output += '    </table>';
		output += '</div>';

		// var output = $(".body-notif").html();
		// output += generate_modal_spk(data);

		// var footer = "";
		// footer += '<div class="modal-footer">';
		// footer += ' <div class="row">';
		// footer += '     <div class="col-md-6 col-md-offset-3 text-center">';
		// footer += '         <button class="btn btn-danger" type="reset" data-dismiss="modal">Tutup</button>';
		// footer += '     </div>';
		// footer += ' </div>';
		// footer += '</div>';


		KIRANAKU.showLoading();

		$("#KiranaModals .modal-title").html(title);
		$('#KiranaModals .modal-body').html(output);

		$('#KiranaModals .modal-body .box-body').slimscroll({
			height: "500px",
			color: "rgba(0,0,0,0.2)",
			size: "5px",
			alwaysVisible: true
		});

		KIRANAKU.hideLoading();

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	});

});


function getNotificationData() {
	// $.ajax({
	//     url: baseURL + 'notifications/get_notifications',
	//     type: 'POST',
	//     dataType: 'JSON',
	//     success: function (data) {
	//         $('.notification-badge').addClass('hide');
	//         $('.notification-badge').html(0);
	//         if (data) {
	//             if ($('.notif-wrapper').length > 0) {
	//                 $('.notif-wrapper .notif-body ul').html(data.notifications);
	//
	//                 // $(".tableTree").treeFy({
	//                 //     initStatusClass: 'treetable-collapsed',
	//                 //     treeColumn: 0,
	//                 //     expanderExpandedClass: 'fa fa-minus-circle',
	//                 //     expanderCollapsedClass: 'fa fa-plus-circle'
	//                 // });
	// 				$(".tableTree").treetable({ expandable: true });
	//             }
	//
	//             if (data.data.length > 0) {
	//                 data.data.map(function (cat) {
	//                     $('.notification-badge[data-code="null"]').addClass('hide');
	//                     $('.notification-badge:not([data-code="null"])').each(function (i, el) {
	//                         var codes = $(this).attr('data-code').split('.');
	//                         var isHaveCode = false;
	//                         $.each(codes, function (i, v) {
	//                             if (v == cat.alias_code)
	//                                 isHaveCode = true;
	//                         });
	//
	//                         if (isHaveCode) {
	//                             let num = parseInt($(el).html());
	//                             num = num + parseInt(cat.notification_count);
	//                             $(this).html(num);
	//                             $(this).removeClass('hide');
	//                         }
	//                     });
	//                 });
	//             }
	//         } else {
	//             if ($('.notif-wrapper').length > 0) {
	//                 $('.notif-wrapper .notif-body ul').html(
	//                     '<li class="item no-notification">\n' +
	//                     '<div class="well text-center">No notification</div>\n' +
	//                     '</li>'
	//                 );
	//             }
	//
	//         }
	//     },
	//     complete: function () {
	// 		$(".indenter",'.notif-wrapper').css("float", "left");
	// 		$(".indenter a",'.notif-wrapper').addClass("fa fa-plus-circle");
	// 		$(".indenter a",'.notif-wrapper').css("color", "black");
	// 		$(".indenter a",'.notif-wrapper').css("background-image", "none");
	//
	//     }
	// });

	$.ajax({
		url: baseURL + 'notifications/get_notifications3',
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			$('.notification-badge').addClass('hide');
			$('.notification-badge').html(0);
			if (data) {
				if ($('.slide-notif').length > 0) {
					$('.slide-notif').html(data.notifications);

					// $(".tableTree").treeFy({
					//     initStatusClass: 'treetable-collapsed',
					//     treeColumn: 0,
					//     expanderExpandedClass: 'fa fa-minus-circle',
					//     expanderCollapsedClass: 'fa fa-plus-circle'
					// });
					$(".tableTree").treetable({expandable: true});
				}

				if (data.data.length > 0) {
					data.data.map(function (cat) {
						$('.notification-badge[data-code="null"]').addClass('hide');
						$('.notification-badge:not([data-code="null"])').each(function (i, el) {
							var codes = $(this).attr('data-code').split('.');
							var isHaveCode = false;
							$.each(codes, function (i, v) {
								if (v == cat.alias_code)
									isHaveCode = true;
							});

							if (isHaveCode) {
								let num = parseInt($(el).html());
								num = num + parseInt(cat.notification_count);
								$(this).html(num);
								$(this).removeClass('hide');
							}
						});
					});
				} else {
					$('.slide-notif').html("");
				}
			} else {
				$('.slide-notif').html("");

			}

			// $('.notification-badge').addClass('hide');
			// $('.notification-badge').html(0);
			// if (data) {
			//     if ($('.slide-notif').length > 0) {
			//         $('.slide-notif').html(data.notifications);

			//         // $(".tableTree").treeFy({
			//         //     initStatusClass: 'treetable-collapsed',
			//         //     treeColumn: 0,
			//         //     expanderExpandedClass: 'fa fa-minus-circle',
			//         //     expanderCollapsedClass: 'fa fa-plus-circle'
			//         // });
			//         $(".tableTree").treetable({ expandable: true });
			//     }

			//     if (data.data.length > 0) {
			//         data.data.map(function (cat) {
			//             $('.notification-badge[data-code="null"]').addClass('hide');
			//             $('.notification-badge:not([data-code="null"])').each(function (i, el) {
			//                 var codes = $(this).attr('data-code').split('.');
			//                 var isHaveCode = false;
			//                 $.each(codes, function (i, v) {
			//                     if (v == cat.alias_code)
			//                         isHaveCode = true;
			//                 });

			//                 if (isHaveCode) {
			//                     let num = parseInt($(el).html());
			//                     num = num + parseInt(cat.notification_count);
			//                     $(this).html(num);
			//                     $(this).removeClass('hide');
			//                 }
			//             });
			//         });
			//     }
			// } else {
			//     $('.slide-notif').html("");

			// }
		},
		complete: function () {
			$(".indenter", '.notif-wrapper').css("float", "left");
			$(".indenter a", '.notif-wrapper').addClass("fa fa-plus-circle");
			$(".indenter a", '.notif-wrapper').css("color", "black");
			$(".indenter a", '.notif-wrapper').css("background-image", "none");

		}
	});

	// $.ajax({
	//     url: baseURL + 'notifications/get_notifications2',
	//     type: 'POST',
	//     dataType: 'JSON',
	//     success: function (data) {
	//         $('.notification-badge').addClass('hide');
	//         $('.notification-badge').html(0);
	//         if (data) {
	//             if ($('.slide-notif-box').length > 0) {
	//                 $('.slide-notif-box').html(data.notifications);

	//                 // $(".tableTree").treeFy({
	//                 //     initStatusClass: 'treetable-collapsed',
	//                 //     treeColumn: 0,
	//                 //     expanderExpandedClass: 'fa fa-minus-circle',
	//                 //     expanderCollapsedClass: 'fa fa-plus-circle'
	//                 // });
	//                 $(".tableTree").treetable({ expandable: true });
	//             }

	//             if (data.data.length > 0) {
	//                 data.data.map(function (cat) {
	//                     $('.notification-badge[data-code="null"]').addClass('hide');
	//                     $('.notification-badge:not([data-code="null"])').each(function (i, el) {
	//                         var codes = $(this).attr('data-code').split('.');
	//                         var isHaveCode = false;
	//                         $.each(codes, function (i, v) {
	//                             if (v == cat.alias_code)
	//                                 isHaveCode = true;
	//                         });

	//                         if (isHaveCode) {
	//                             let num = parseInt($(el).html());
	//                             num = num + parseInt(cat.notification_count);
	//                             $(this).html(num);
	//                             $(this).removeClass('hide');
	//                         }
	//                     });
	//                 });
	//             }
	//         } else {
	//             $('.slide-notif-box').html("");

	//         }

	//         $('.notification-notif-box').addClass('hide');
	//         $('.notification-notif-box').html(0);
	//         if (data) {
	//             if ($('.slide-notif-box').length > 0) {
	//                 $('.slide-notif-box').html(data.notifications);

	//                 // $(".tableTree").treeFy({
	//                 //     initStatusClass: 'treetable-collapsed',
	//                 //     treeColumn: 0,
	//                 //     expanderExpandedClass: 'fa fa-minus-circle',
	//                 //     expanderCollapsedClass: 'fa fa-plus-circle'
	//                 // });
	//                 $(".tableTree").treetable({ expandable: true });
	//             }

	//             if (data.data.length > 0) {
	//                 data.data.map(function (cat) {
	//                     $('.notification-badge[data-code="null"]').addClass('hide');
	//                     $('.notification-badge:not([data-code="null"])').each(function (i, el) {
	//                         var codes = $(this).attr('data-code').split('.');
	//                         var isHaveCode = false;
	//                         $.each(codes, function (i, v) {
	//                             if (v == cat.alias_code)
	//                                 isHaveCode = true;
	//                         });

	//                         if (isHaveCode) {
	//                             let num = parseInt($(el).html());
	//                             num = num + parseInt(cat.notification_count);
	//                             $(this).html(num);
	//                             $(this).removeClass('hide');
	//                         }
	//                     });
	//                 });
	//             }
	//         } else {
	//             $('.slide-notif-box').html("");

	//         }
	//     },
	//     complete: function () {
	//         $(".indenter",'.notif-wrapper').css("float", "left");
	//         $(".indenter a",'.notif-wrapper').addClass("fa fa-plus-circle");
	//         $(".indenter a",'.notif-wrapper').css("color", "black");
	//         $(".indenter a",'.notif-wrapper').css("background-image", "none");

	//     }
	// });

}

function getNotificationData2() {
	$.ajax({
		url: baseURL + 'notifications/get_notifications3',
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			$('.notification-badge').addClass('hide');
			$('.notification-badge').html(0);
			if (data) {
				if ($('.slide-notif').length > 0) {
					$('.slide-notif').html(data.notifications);

					// $(".tableTree").treeFy({
					//     initStatusClass: 'treetable-collapsed',
					//     treeColumn: 0,
					//     expanderExpandedClass: 'fa fa-minus-circle',
					//     expanderCollapsedClass: 'fa fa-plus-circle'
					// });
					$(".tableTree").treetable({expandable: true});
				}

				if (data.data.length > 0) {
					data.data.map(function (cat) {
						$('.notification-badge[data-code="null"]').addClass('hide');
						$('.notification-badge:not([data-code="null"])').each(function (i, el) {
							var codes = $(this).attr('data-code').split('.');
							var isHaveCode = false;
							$.each(codes, function (i, v) {
								if (v == cat.alias_code)
									isHaveCode = true;
							});

							if (isHaveCode) {
								let num = parseInt($(el).html());
								num = num + parseInt(cat.notification_count);
								$(this).html(num);
								$(this).removeClass('hide');
							}
						});
					});
				} else {
					$('.slide-notif').html("");
				}
			} else {
				$('.slide-notif').html("");

			}

			// $('.notification-badge').addClass('hide');
			// $('.notification-badge').html(0);
			// if (data) {
			//     if ($('.slide-notif').length > 0) {
			//         $('.slide-notif').html(data.notifications);

			//         // $(".tableTree").treeFy({
			//         //     initStatusClass: 'treetable-collapsed',
			//         //     treeColumn: 0,
			//         //     expanderExpandedClass: 'fa fa-minus-circle',
			//         //     expanderCollapsedClass: 'fa fa-plus-circle'
			//         // });
			//         $(".tableTree").treetable({ expandable: true });
			//     }

			//     if (data.data.length > 0) {
			//         data.data.map(function (cat) {
			//             $('.notification-badge[data-code="null"]').addClass('hide');
			//             $('.notification-badge:not([data-code="null"])').each(function (i, el) {
			//                 var codes = $(this).attr('data-code').split('.');
			//                 var isHaveCode = false;
			//                 $.each(codes, function (i, v) {
			//                     if (v == cat.alias_code)
			//                         isHaveCode = true;
			//                 });

			//                 if (isHaveCode) {
			//                     let num = parseInt($(el).html());
			//                     num = num + parseInt(cat.notification_count);
			//                     $(this).html(num);
			//                     $(this).removeClass('hide');
			//                 }
			//             });
			//         });
			//     }
			// } else {
			//     $('.slide-notif').html("");

			// }
		},
		complete: function () {
			$(".indenter", '.notif-wrapper').css("float", "left");
			$(".indenter a", '.notif-wrapper').addClass("fa fa-plus-circle");
			$(".indenter a", '.notif-wrapper').css("color", "black");
			$(".indenter a", '.notif-wrapper').css("background-image", "none");

			setTimeout(function () {
				getNotificationData();
			}, 60000);
		}
	});

	// $.ajax({
	//     url: baseURL + 'notifications/get_notifications2',
	//     type: 'POST',
	//     dataType: 'JSON',
	//     success: function (data) {
	//         $('.notification-badge').addClass('hide');
	//         $('.notification-badge').html(0);
	//         if (data) {
	//             if ($('.slide-notif-box').length > 0) {
	//                 $('.slide-notif-box').html(data.notifications);

	//                 // $(".tableTree").treeFy({
	//                 //     initStatusClass: 'treetable-collapsed',
	//                 //     treeColumn: 0,
	//                 //     expanderExpandedClass: 'fa fa-minus-circle',
	//                 //     expanderCollapsedClass: 'fa fa-plus-circle'
	//                 // });
	//                 $(".tableTree").treetable({ expandable: true });
	//             }

	//             if (data.data.length > 0) {
	//                 data.data.map(function (cat) {
	//                     $('.notification-badge[data-code="null"]').addClass('hide');
	//                     $('.notification-badge:not([data-code="null"])').each(function (i, el) {
	//                         var codes = $(this).attr('data-code').split('.');
	//                         var isHaveCode = false;
	//                         $.each(codes, function (i, v) {
	//                             if (v == cat.alias_code)
	//                                 isHaveCode = true;
	//                         });

	//                         if (isHaveCode) {
	//                             let num = parseInt($(el).html());
	//                             num = num + parseInt(cat.notification_count);
	//                             $(this).html(num);
	//                             $(this).removeClass('hide');
	//                         }
	//                     });
	//                 });
	//             }
	//         } else {
	//             $('.slide-notif-box').html("");

	//         }

	//         $('.notification-notif-box').addClass('hide');
	//         $('.notification-notif-box').html(0);
	//         if (data) {
	//             if ($('.slide-notif-box').length > 0) {
	//                 $('.slide-notif-box').html(data.notifications);

	//                 // $(".tableTree").treeFy({
	//                 //     initStatusClass: 'treetable-collapsed',
	//                 //     treeColumn: 0,
	//                 //     expanderExpandedClass: 'fa fa-minus-circle',
	//                 //     expanderCollapsedClass: 'fa fa-plus-circle'
	//                 // });
	//                 $(".tableTree").treetable({ expandable: true });
	//             }

	//             if (data.data.length > 0) {
	//                 data.data.map(function (cat) {
	//                     $('.notification-badge[data-code="null"]').addClass('hide');
	//                     $('.notification-badge:not([data-code="null"])').each(function (i, el) {
	//                         var codes = $(this).attr('data-code').split('.');
	//                         var isHaveCode = false;
	//                         $.each(codes, function (i, v) {
	//                             if (v == cat.alias_code)
	//                                 isHaveCode = true;
	//                         });

	//                         if (isHaveCode) {
	//                             let num = parseInt($(el).html());
	//                             num = num + parseInt(cat.notification_count);
	//                             $(this).html(num);
	//                             $(this).removeClass('hide');
	//                         }
	//                     });
	//                 });
	//             }
	//         } else {
	//             $('.slide-notif-box').html("");

	//         }
	//     },
	//     complete: function () {
	//         $(".indenter",'.notif-wrapper').css("float", "left");
	//         $(".indenter a",'.notif-wrapper').addClass("fa fa-plus-circle");
	//         $(".indenter a",'.notif-wrapper').css("color", "black");
	//         $(".indenter a",'.notif-wrapper').css("background-image", "none");

	//     }
	// });


}




