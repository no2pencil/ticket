$(document).ready(function() {
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}
		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});


	$('#ticket_delete').dialog({
		autoOpen: false,
		width: 500,
		buttons: {
			"Delete": function() {
				window.location.href = "tickets.php?delete=" + $_GET['view'] + '&dodelete=yes';
			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}
	});
	
	$('#ticket_delete_link').click(function(){
		$('#ticket_delete').dialog('open');
		return false;
	});
});
