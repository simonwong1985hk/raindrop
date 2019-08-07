# .env
MAIL_DRIVER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=

# app/Mail/OrderShipped.php
php artisan make:mail OrderShipped
use App\Order;
class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->view('emails.orders.shipped');
    }
}

# resources/views/emails/orders/shipped.blade
<div>
    Price: {{ $order->price }}
</div>

# app/Http/Controllers/OrderController.php
<?php
namespace App\Http\Controllers;

use App\Order;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function ship(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        Mail::to($request->user())->send(new OrderShipped($order));
    }
}
