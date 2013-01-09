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
</script>
