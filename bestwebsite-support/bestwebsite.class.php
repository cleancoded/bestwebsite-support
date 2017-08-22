<?php
/**
Support Form class
*/

class BestWebsite_Support
{
	
	function __construct()
	{
		# code...
		add_action('admin_menu', array($this, 'support_form_menu'));
	}

	public function support_form_menu()
	{
		# code...
		add_menu_page(
			__('Support', 'bestwebsite-support'),
			'Support',
			'manage_options',
			'custompage',
			$this->support_form(),
			'',
			'',
			10);
	}

	public function support_form()
	{
		# code...
		ob_start();
		?>
		<div class="bestwebsite_support_form">
		<input type="text" name="bestwebsite_support_subject" id="bw_support_subject" />
		<input type="hidden" name="bestwebsite_support_clienturl" id="bw_support_clienturl" />
		<input type="email" name="bestwebsite_support_clientemail" id="bw_support_clientemail" />
		<textarea cols="10" rows="10" name="bestwebsite_support_msg" id="bw_support_msg" />
			
		</textarea>

		<button type="button" class="bw-button" id="submit_bw_ticket">Submit Ticket</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
$bestwebsite_support = new BestWebsite_Support();