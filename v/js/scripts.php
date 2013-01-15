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
</script>
