<?php
session_start();

$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;
$method = isset($_POST['method']) ? $_POST['method'] : null;
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;

if(!$order_id) {
    header('Location: create_order.php');
    exit;
}

require_once '../app/Payment.php';

if($method != 'cash') {
    $card_id = isset($_POST['card_id']) ? $_POST['card_id'] : null;
    $payment_recorded = record_card_payment($order_id, $card_id, $amount, $method);
} else {
    $payment_recorded = record_cash_payment($order_id, $amount, 'cash');

}
?>

<html>
    <body>
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