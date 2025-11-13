<?php
session_start();

require_once '../app/Orders.php';

$user_id = $_SESSION['user_id'];

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;
$order_data = get_order_by_id($order_id);
$total_amount = $order_data ? $order_data['total_amount'] : 0.00;

if(isset($_POST['payment_submit'])){    
    $card_id = isset($_POST['card_id']) ? $_POST['card_id'] : null;
    $method = isset($_POST['method']) ? $_POST['method'] : null;
    require_once '../app/Payment.php';


    if($method != 'cash') {
        $card_id = isset($_POST['card_id']) ? $_POST['card_id'] : null;
        $payment_recorded = record_card_payment($order_id, $card_id, $amount, $method);
    } else {
        $payment_recorded = record_cash_payment($order_id, $amount, 'cash');

    }
    $payment_recorded = true;
}
?>

<html>
    <head>
        <title>Payment Processing</title>
        <link rel="stylesheet" type="text/css" href="css/navbar.css?">
        
    </head>
    <body>
        <nav>
            <h1>RETAILO</h1>
            <h3>Payment Page</h3>
        </nav>
        <div class="container">
            <form action="">
                <h2>Processing Payment for Order ID: <?php echo htmlspecialchars($order_id); ?></h2>
                <h3>Total amount: <?= number_format($total_amount, 2) ?></h3>
                <select name="method">
                    <option value="cash"></option>
                    <option value="Cr"></option>
                    <option value=""></option>
                </select>
            </form>
        </div>
        <?php if($payment_recorded): ?>
            <h2>Payment Successful!</h2>
            <p>Your payment for Order ID <?php echo htmlspecialchars($order_id); ?> has been processed successfully.</p>
            <a href="orders.php">Go to Orders</a>
        <?php else: ?>
            <h2>Payment Failed!</h2>
            <p>There was an issue processing your payment for Order ID <?php echo htmlspecialchars($order_id); ?>. Please try again.</p>
            <a href="payment.php?order_id=<?php echo htmlspecialchars($order_id); ?>">Retry Payment</a>
        <?php endif; ?>
    </body>
</html>