<?php
	require("functions/functions.php");
	require("connection/connection.php");
	session_start();
	if(!isset($_GET['route'])){
		$_GET['route'] = "";
	}

	switch (filter($_GET['route'])) {
		case '':
			require("view/home.php");
			break;
		case 'home':
			require("view/home.php");
			break;
		case 'apply':
			require("view/applyAsHandyman.php");
			break;
		case 'create':
			require("view/createAccountCustomer.php");
			break;
		case 'handyman_profile':
			require("view/handymanProfile.php");
			break;
		case 'handyman_history':
			require("view/handymanHistory.php");
			break;
		case 'handyman_requirements':
			require("view/handyman_requirements.php");
			break;
		case 'receipt':
			require("view/receipt.php");
			break;
		case 'topup_history':
			require("view/topupHistory.php");
			break;
		case 'topup_receipt':
			require("view/topupreceipt.php");
			break;

		case 'customer_profile':
			require("view/customerProfile.php");
			break;
		case 'customer_history':
			require("view/customerHistory.php");
			break;
		case 'customer_requirements':
			require("view/customer_requirements.php");
			break;
		case 'logout':
			require("view/logout.php");
			break;
		default:
			echo '<br>'.$_GET['route'];
			break;
	}
?>