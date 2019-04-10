<?php
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2013 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  Lincence details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (!defined('DIR_CORE')) {
	header('Location: static_pages/');
}
/**
 * @property ModelExtensionnochex $model_extension_nochex
 * @property ModelCheckoutOrder $model_checkout_order
 */
 
class ControllerResponsesExtensionNochex extends AController {
	public function main() {
		$this->loadLanguage('nochex/nochex');
		$template_data['button_confirm'] = $this->language->get('button_confirm');
		$template_data['button_back'] = $this->language->get('button_back');	
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);		
		$debugInfo = "Order Information: " . $this->session->data['order_id'];
		$this->nochex_debug($debugInfo);
		//Nochex Link

		$template_data['action'] = 'https://secure.nochex.com/default.aspx';
		
		// Nochex parameters
		$template_data['merchant_id'] = $this->config->get('nochex_account');
		$template_data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$template_data['order_id'] = $this->session->data['order_id'];
		//$template_data['optional_1'] = 
		$orderDetails = "Order Information - Template_Data - Merchant ID - : " . $template_data['merchant_id'] . ", Amount : " . $order_info['total'] . ", Order ID : " . $template_data['order_id'] . ", ";
		$this->nochex_debug($orderDetails);
		
		//$order_info['shipping_method']
		if ($this->config->get('nochex_postage') == 1){
		
		$template_data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE) - $this->session->data['shipping_method']['cost'];
		$template_data['postage'] = $this->currency->format($this->session->data['shipping_method']['cost'], $order_info['currency'], $order_info['value'], FALSE);
		}else{
		$template_data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$template_data['postage'] = "";
		}
		
		$shipping = $this->session->data['shipping_method']['cost'];
		
		$template_data['billing_fullname'] = $order_info['payment_firstname'] . ', ' . $order_info['payment_lastname'];
		$template_data['billing_address'] = $order_info['payment_address_1'];	
		$template_data['billing_city'] = $order_info['payment_city'];
		$template_data['billing_postcode'] = $order_info['payment_postcode'];
		$template_data['customer_phone_number'] = str_replace("+", "", $order_info['telephone']);	
		
		if ($this->config->get('nochex_callback') == 1){
			$template_data['optional_2'] = "ENABLED";
		}else{
			$template_data['optional_2'] = "";
		}
		 
		if ($this->config->get('hide_billing_details') == 1){
			$template_data['hide_billing_details'] = "true";
		}else{
			$template_data['hide_billing_details'] = "false";
		}
		
		$template_data['email_address'] = $order_info['email'];
		
		$billAddress = "Order Information - Template_Data - Billing Address - : " . $template_data['billing_fullname'] . ", " . $template_data['billing_address'] . ", " . $template_data['billing_postcode'] . ", " . $template_data['customer_phone_number'] . ", " . $template_data['email_address'] . ", ";
		$this->nochex_debug($billAddress);
		
		if ($order_info['shipping_lastname']) {
			$template_data['delivery_fullname'] = $order_info['shipping_firstname'] . ', ' . $order_info['shipping_lastname'];
		} else {
			$template_data['delivery_fullname'] = $order_info['payment_firstname'] . ', ' . $order_info['payment_lastname'];
		}
		if ($this->cart->hasShipping()) {
			$template_data['delivery_address'] = $order_info['shipping_address_1'];
			$template_data['delivery_city'] = $order_info['shipping_city'];
			$template_data['delivery_postcode'] = $order_info['shipping_postcode'];
		} else {
			$template_data['delivery_address'] = $order_info['payment_address_1'];
			$template_data['delivery_city'] = $order_info['payment_city'];
			$template_data['delivery_postcode'] = $order_info['payment_postcode'];
		}
		
		$delAddress = "Order Information - Template_Data - Delivery Address - : " . $template_data['delivery_fullname'] . ", " . $template_data['delivery_address'] . ", " . $template_data['delivery_postcode'] . ", " ;
		$this->nochex_debug($delAddress);
		
		$template_data['callback_url'] = $this->html->getSecureURL('extension/nochex/callback');
		$template_data['success_url'] = $this->html->getSecureURL('checkout/success');
		$template_data['test_success_url'] = $this->html->getSecureURL('checkout/success');
		$template_data['cancel_url'] = $this->html->getSecureURL('extension/nochex/cancelledOrder');
		
		$tempUrl = "Order Information - Template_Data - Callback - : " . $template_data['callback_url'] . ", Success - : " . $template_data['success_url'] . ", Test Success - : " . $template_data['test_success_url'] . ", Cancel_url - : " . $template_data['cancel_url'] . ", ";
		$this->nochex_debug($tempUrl);
		
		// Product details
		$template_data['products'] = array();
		$products = $this->cart->getProducts();
		
		if($this->config->get('nochex_xml') == 1){
		$template_data['xml_item_collection'] = "<items>";
		foreach ($products as $product) {
			$template_data['xml_item_collection'] .= "<item><id>" . $product['product_id'] . "</id><name>" . $product['name'] . "</name><description>" . $product['name'] . ", ". $product['model']  ."</description><quantity>" . $product['quantity'] . "</quantity><price>". $this->currency->format($product['price'], $order_info['currency'], $order_info['value'], FALSE)."</price></item>";
		}
		
		$template_data['xml_item_collection'] .= "</items>";
		$template_data['description'] = "Order created for: ". $this->session->data['order_id'];
		
		}else{
		
		$template_data['xml_item_collection'] = "";
		$template_data['description'] = "";
		
		foreach ($products as $product) {
			$template_data['description'] .= "  Product ID: " . $product['product_id'] . ", Product Name: " . $product['name'] . ", Product Description: " . $product['description'] . ", Product Quantity: " . $product['quantity'] . ", Product Price: ". $this->currency->format($product['price'], $order_info['currency'], $order_info['value'], FALSE);
		}
		
		$template_data['description'] .= ". ";
		
		}
		
		//		
		if ($this->request->get['rt'] != 'checkout/guest_step_3') {
			$template_data['back'] = $this->html->getSecureURL('checkout/payment');
		} else {
			$template_data['back'] = $this->html->getSecureURL('checkout/guest_step_2');
		}
		$tempInfo = "Order Information - Template_Data - Description - : " . $template_data['description'] . ", ";
		$this->nochex_debug($tempInfo);
		$this->view->batchAssign($template_data);
		$this->processTemplate('responses/nochex.tpl');
	}
	
	public function nochex_debug($DebugData){
	
	$nochex_debug = $this->config->get('nochex_debug');
	// If the control nochex_debug has been checked in the module config, then it will use data sent and received in this function which will write to the nochex_debug file
		if ($nochex_debug == 1){
		// Receives and stores the Date and Time
		$debug_TimeDate = date("m/d/Y h:i:s a", time());
		// Puts together, Date and Time, as well as information in regards to information that has been received.
		$stringData = "\n Time and Date: " . $debug_TimeDate . "... " . $DebugData ."... ";
		 // Try - Catch in case any errors occur when writing to nochex_debug file.
			try
			{
			// Variable with the name of the debug file.
				$debugging = "../abantecart/extensions/nochex/nochex_debug.txt";
			// variable which will open the nochex_debug file, or if it cannot open then an error message will be made.
				$f = fopen($debugging, 'a') or die("File can't open");
			// Open and write data to the nochex_debug file.
			$ret = fwrite($f, $stringData);
			// Incase there is no data being shown or written then an error will be produced.
			if ($ret === false)	die("Fwrite failed");
				// Closes the open file.
				fclose($f)or die("File not close");
			} 
			//If a problem or something doesn't work, then the catch will produce an email which will send an error message.
			catch(Exception $e)
			{
			mail($to, "Debug Check Error Message", $e->getMessage());
			}
		}
	}
	public function cancelledOrder(){
	//
	$this->redirect($this->html->getSecureURL('checkout/cart'));	
	//
	}
	
	public function callback() {
		$this->load->model('checkout/order');
		$order_id = (int)$this->request->post['order_id'];
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$this->load->model('extension/nochex');
		$postvars = http_build_query($_POST);
		
		if (isset($_POST["optional_2"]) == "ENABLED"){
		//
		// Set parameters for the email
		//
		$to = '';
		$url = "https://secure.nochex.com/callback/callback.aspx";
		// Curl code to post variables back
		$ch = curl_init(); // Initialise the curl tranfer
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); // Set POST fields
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: secure.nochex.com"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); // set connection time out variable - 60 seconds	
		curl_setopt ($ch, CURLOPT_SSLVERSION, 6); // set openSSL version variable to CURL_SSLVERSION_TLSv1
		$output = curl_exec($ch); // Post back
		curl_close($ch);
		if ($this->config->get('nochex_test') == 1){
			$testt = 'This was a test transaction!';
		}else{
			$testt = 'This was a live transaction!';
		}
		if ($output == "AUTHORISED") {   
			mail($to,"Callback - Authorised"," Callback Output:". $output);
			$this->model_checkout_order->confirm($order_id, $this->config->get('nochex_order_status_id') );
			
			$this->model_checkout_order->updatePaymentMethodData($order_id,'Status updated by Nochex, Callback Authorised! ' . $testt);
			
			$tempUrl = "Callback - Output Details - Authorised : ". $output . ", Order ID : " . $order_id . ", Nochex Order Status ID : 5";
			$this->nochex_debug($tempUrl);
		}else if ($output == "Declined"){
			mail($to,"Callback - Declined"," Callback Output:". $output);
			$this->model_checkout_order->confirm($order_id, 10);
			
			$this->model_checkout_order->updatePaymentMethodData($order_id,'Status updated by Nochex, Callback Declined! '. $testt);
			
			$tempUrl = "Callback - Output Details - Declined : ". $output . ", Order ID : " . $order_id . ", Nochex Order Status ID : 10";
			$this->nochex_debug($tempUrl);
		}
		
		}else{
		//
		// Set parameters for the email
		//
		$to = '';
		$url = "https://www.nochex.com/apcnet/apc.aspx";
		// Curl code to post variables back
		$ch = curl_init(); // Initialise the curl tranfer
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); // Set POST fields
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: www.nochex.com"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); // set connection time out variable - 60 seconds	
		curl_setopt ($ch, CURLOPT_SSLVERSION, 6); // set openSSL version variable to CURL_SSLVERSION_TLSv1
		$output = curl_exec($ch); // Post back
		curl_close($ch);
		if ($this->config->get('nochex_test') == 1){
			$testt = 'This was a test transaction!';
		}else{
			$testt = 'This was a live transaction!';
		}
		if ($output == "AUTHORISED") {   
			mail($to,"APC - Authorised"," APC Output:". $output);
			$this->model_checkout_order->confirm($order_id, $this->config->get('nochex_order_status_id') );
			$this->model_checkout_order->updatePaymentMethodData($order_id,'Status updated by Nochex, APC Authorised! ' . $testt);
			$tempUrl = "Callback - Output Details - Authorised : ". $output . ", Order ID : " . $order_id . ", Nochex Order Status ID : 5";
			$this->nochex_debug($tempUrl);
		}else if ($output == "Declined"){
			mail($to,"APC - Declined"," APC Output:". $output);
			$this->model_checkout_order->confirm($order_id, 10);
			$this->model_checkout_order->updatePaymentMethodData($order_id,'Status updated by Nochex, APC Declined! '. $testt);
			$tempUrl = "Callback - Output Details - Declined : ". $output . ", Order ID : " . $order_id . ", Nochex Order Status ID : 10";
			$this->nochex_debug($tempUrl);
		}
		
		}
	}
}
