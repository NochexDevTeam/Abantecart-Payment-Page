
<form action="<?php echo $action; ?>" method="get" id="checkout">
	<input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>"/>
	<input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
	<input type="hidden" name="postage" value="<?php echo $postage; ?>"/>
	<input type="hidden" name="xml_item_collection" value="<?php echo $xml_item_collection; ?>"/>
	<input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
	<input type="hidden" name="optional_1" value="<?php echo $optional_1; ?>"/>	
	<input type="hidden" name="optional_2" value="<?php echo $optional_2; ?>"/>	
	<input type="hidden" name="billing_fullname" value="<?php echo $billing_fullname; ?>"/>
	<input type="hidden" name="billing_address" value="<?php echo $billing_address; ?>"/>
	<input type="hidden" name="billing_city" value="<?php echo $billing_city; ?>"/>
	<input type="hidden" name="billing_postcode" value="<?php echo $billing_postcode; ?>"/>
	<input type="hidden" name="email_address" value="<?php echo $email_address; ?>"/>
	<input type="hidden" name="customer_phone_number" value="<?php echo $customer_phone_number; ?>"/>
	<input type="hidden" name="delivery_fullname" value="<?php echo $delivery_fullname; ?>"/>
	<input type="hidden" name="delivery_address" value="<?php echo $delivery_address; ?>"/>
	<input type="hidden" name="delivery_city" value="<?php echo $delivery_city; ?>"/>
	<input type="hidden" name="delivery_postcode" value="<?php echo $delivery_postcode; ?>"/>
	<input type="hidden" name="description" value="<?php echo $description; ?>" />
	<input type="hidden" name="test_success_url" value="<?php echo $test_success_url; ?>"/>
	<input type="hidden" name="test_transaction" value="100"/>
	<input type="hidden" name="success_url" value="<?php echo $success_url; ?>"/>
	<input type="hidden" name="callback_url" value="<?php echo $callback_url; ?>"/>
	<input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>"/>
	
	<div class="control-group">
	    <div class="controls">
	    	<button class="btn btn-orange pull-right" title="<?php echo $button_confirm; ?>" type="submit">
	    	    <i class="icon-ok icon-white"></i>
	    	    <?php echo $button_confirm; ?>
	    	</button>
	    	<a href="<?php echo str_replace('&', '&amp;', $back); ?>" class="btn mr10" title="<?php echo $button_back; ?>">
	    	    <i class="icon-arrow-left"></i>
	    	    <?php echo $button_back; ?>
	    	</a>
	    </div>
	</div>
</form>

