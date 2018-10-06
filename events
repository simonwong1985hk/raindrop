php artisan event:generate

Event.php
public $order;
public function __construct(OrderShipped $order)
{
	$this->order = $order;
}

EventListener.php
public function handle(OrderShipped $event)
{
// Access the order using $event->order...
}

event(new Event($order));
