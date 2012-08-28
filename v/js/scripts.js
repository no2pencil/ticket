// These are global scripts, ran on every page
$(document).ready(function() {
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}
		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});
	
	
	$('#').click(function() {
		return false;
	});
	
	$(".chzn-select").chosen();
	$(".chzn-select-deselect").chosen({allow_single_deselect:true});
});
