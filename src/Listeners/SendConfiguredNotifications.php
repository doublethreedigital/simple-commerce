<?php

namespace DoubleThreeDigital\SimpleCommerce\Listeners;

use DoubleThreeDigital\SimpleCommerce\Events\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionParameter;

class SendConfiguredNotifications implements ShouldQueue
{
    public function handle(OrderPaid $event)
    {
        $eventName = Str::of(get_class($event))
            ->afterLast('\\')
            ->snake()
            ->__toString();

        $notifications = collect(Config::get('simple-commerce.notifications'))->get($eventName);

        foreach ($notifications as $notification => $config) {
            $freshNotification = null;

            $notifiables = $this->getNotifiables($config, $notification, $event);
            $notification = new $notification(...$this->getNotificationParameters($config, $notification, $event));

            foreach ($notifiables as $channel => $route) {
                if (! $freshNotification) {
                    $freshNotification = Notification::route($channel, $route);
                } else {
                    $freshNotification->route($channel, $route);
                }
            }

            optional($freshNotification)->notify($notification);
        }
    }

    protected function getNotifiables(array $config, $notification, $event): array
    {
        if ($config['to'] === 'customer') {
            if ($customer = $event->order->customer()) {
                return [
                    ['channel' => 'mail', 'route' => $customer->email()],
                ];
            }

            if ($email = $event->order->get('email')) {
                return [
                    ['channel' => 'mail', 'route' => $email],
                ];
            }

            if ($email = $event->order->get('customer')) {
                return [
                    ['channel' => 'mail', 'route' => $email],
                ];
            }
        }

        if (is_string($config['to'])) {
            return [
                ['channel' => 'mail', 'route' => $config['to']]
            ];
        }

        throw new \Exception("No notifiable specified for [{$notification}]");
    }

    protected function getNotificationParameters(array $config, $notification, $event): array
    {
        $reflection = new ReflectionClass($notification);
        $constructor = $reflection->getConstructor();

        return collect($constructor->getParameters())
            ->map(function (ReflectionParameter $parameter) use ($event) {
                if (property_exists($event, $parameter->getName())) {
                    return $event->{$parameter->getName()};
                }

                throw new \Exception("A parameter called [{$parameter->getName()}] does not exist on the event.");
            })
            ->toArray();
    }
}
