<?php
	/*
	 * All jquery code goes in this file...
	 */
?>
<script>
	var selects = $('.chzn-select');
	selects.chosen().change(function() {
		var selected = [];
		selects.find("option").each(function() {
			if(this.selected) {
				selected[this.value] = this;
			}
			$('#customers_select_form').submit();
		});
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
