composer require laravel/cashier

php artisan vendor:publish --tag="cashier-migrations"

php artisan migrate

# app/User.php
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Billable;
}

# .env
STRIPE_KEY=your-stripe-key
STRIPE_SECRET=your-stripe-secret
CASHIER_CURRENCY=HKD
CASHIER_CURRENCY_LOCALE=en_HK

# frontend

# backend
try {
    // Stripe Accepts Charges In Cents...
    $payment = $user->charge(100, $paymentMethod);
} catch (Exception $e) {
    //
}
