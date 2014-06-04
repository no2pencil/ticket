<script>
	var selects = $('.chzn-select');
	selects.chosen().change(function() {
		var selected=[];
		selects.find("option").each(function() {
			if(this.selected) {
				selected[this.value] = this;
			}
			$('#customers_select_form').submit();
		});
	});

	$('#EditCustomerModal').on('shown', function() {
		$('#EditCustomerModal').click();
	});
	$('#NewTicketModal').on('shown', function() {
		$('#NewTicketModal').click();
	});
	$('#NewCustomerModal').on('shown', function() {
		$('#NewCustomerModal').click();
	});
	$('#NewUserModal').on('shown', function () {
		$('#NewUserModal').click();
	});
	$('#StatusesModal').on('shown', function() {
		$('#StatusesModal').click();
	});
	$('#ReferralsModal').on('shown', function() {
		$('#ReferralsModal').click();
	});
	$('.RingUrl').click(function() {
		var id = $(this).data('id');
		var phone = $(this).data('phone');
		var user = $(this).data('user');
		$.post("/ajax/RingCentral.php", { 
			user: user, 
			phone: phone,
			id: id
		},
		function(data) {
			console.log("Logging " + data.user + " making call to " + data.phone + " on invoice " + data.id + " at " + data.time);
		}, "json");
	});

	/* Only Allow Numerical Keypress in Phone Number Input Box */
	$(".Phone").keydown(function(e) {
		var key = e.keyCode;
		if(e.shiftKey) {
			return false;
		}
		if(
			key == 8 ||
			key == 9 ||
			key == 37 ||
			key == 39 ||
			key == 46 ||
			key == 110 ||
			key == 190 || 
			(key >= 48 && key <= 57) ||
			(key >= 96 && key <= 105)) {
			console.log(key);
		} else {
			e.preventDefault();
		}
	});

	$("input").click(function(e) {
		e.preventDefault();
		var id=this.id;
		$('#' + id).hide(500);
		switch(id) {
			case 'add-status':
				var line = '...';
				var action = '#status_management';
			break;
			case 'add-referral':
				//var line = '<tr><td>&nbsp;</td><td><input type="text" name=""></td></tr>';
				var line = '<input type="text" name="">';
				var action = '#referrals_management';
			break;
		}
		console.log("Doing " + action + " on " + line);
		$(action).append(line);
		$('#' + id).show(500);
	});

	$('#save').click(function (e) {
		e.preventDefault();
		$.ajax({
			// Need to dynamically pass url!
			url: 'v/ReferralsEngine.php',
			type: 'POST',
			data: 'test',
			success: function (response) {
				var temp = new Array();
				temp = response.split(" ");
				$("#body span").text(temp[1]);
			},
			error: function (response) {
				var temp = 'Something went horribly wrong!';
			}
		});
	});
</script>
