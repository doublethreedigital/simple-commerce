<?php

namespace DoubleThreeDigital\SimpleCommerce;

use Statamic\Events\EntryBlueprintFound;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Stache\Stache;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $config = false;
    protected $translations = false;

    protected $actions = [
        Actions\MarkAsPaid::class,
        Actions\RefundAction::class,
    ];

    protected $commands = [
        Console\Commands\CartCleanupCommand::class,
        Console\Commands\MakeGateway::class,
        Console\Commands\MakeShippingMethod::class,
        Console\Commands\InstallCommand::class,
    ];

    protected $fieldtypes = [
        Fieldtypes\CountryFieldtype::class,
        Fieldtypes\MoneyFieldtype::class,
        Fieldtypes\ProductVariantFieldtype::class,
        Fieldtypes\ProductVariantsFieldtype::class,
    ];

    protected $listen = [
        EntryBlueprintFound::class  => [
            Listeners\EnforceBlueprintFields::class,
        ],
        Events\OrderPaid::class => [
            Listeners\SendConfiguredNotifications::class,
        ],
        Events\StockRunningLow::class => [
            Listeners\SendConfiguredNotifications::class,
        ],
        Events\StockRunOut::class => [
            Listeners\SendConfiguredNotifications::class,
        ],
    ];

    protected $routes = [
        'actions' => __DIR__.'/../routes/actions.php',
        'cp'      => __DIR__.'/../routes/cp.php',
    ];

    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js',
    ];

    protected $tags = [
        Tags\SimpleCommerceTag::class,
    ];

    protected $widgets = [
        Widgets\SalesWidget::class,
    ];

    protected $updateScripts = [
        UpdateScripts\AddBlueprintFields::class,
        UpdateScripts\MigrateConfig::class,
        UpdateScripts\MigrateLineItemMetadata::class,
    ];

    public function boot()
    {
        parent::boot();

        Statamic::booted(function () {
            $this
                ->bootVendorAssets()
                ->bindContracts()
                ->bootCartDrivers();
        });

        SimpleCommerce::bootGateways();
        SimpleCommerce::bootTaxEngine();

        Statamic::booted(function () {
            $this
                ->bootStacheStores()
                ->createNavItems()
                ->registerPermissions();
        });

        Filters\OrderStatusFilter::register();
    }

    protected function bootVendorAssets()
    {
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/simple-commerce'),
        ], 'simple-commerce');

        $this->publishes([
            __DIR__.'/../config/simple-commerce.php' => config_path('simple-commerce.php'),
        ], 'simple-commerce-config');

        $this->publishes([
            __DIR__.'/../resources/blueprints' => resource_path('blueprints'),
        ], 'simple-commerce-blueprints');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/simple-commerce'),
        ], 'simple-commerce-translations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/simple-commerce'),
        ], 'simple-commerce-views');

        if (app()->environment() !== 'testing') {
            $this->mergeConfigFrom(__DIR__.'/../config/simple-commerce.php', 'simple-commerce');
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'simple-commerce');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'simple-commerce');

        return $this;
    }

    protected function bindContracts()
    {
        collect([
            Contracts\Order::class              => SimpleCommerce::orderDriver()['driver'],
            Contracts\Coupon::class             => SimpleCommerce::couponDriver()['driver'],
            Contracts\Customer::class           => SimpleCommerce::customerDriver()['driver'],
            Contracts\Product::class            => SimpleCommerce::productDriver()['driver'],
            Contracts\GatewayManager::class     => Gateways\Manager::class,
            Contracts\ShippingManager::class    => Shipping\Manager::class,
            Contracts\Currency::class           => Support\Currency::class,
            Contracts\Calculator::class         => Orders\Calculator::class,
        ])->each(function ($concrete, $abstract) {
            if (! $this->app->bound($abstract)) {
                Statamic::repository($abstract, $concrete);
            }
        });

        return $this;
    }

    protected function bootCartDrivers()
    {
        if (! $this->app->bound(Contracts\CartDriver::class)) {
            $this->app->bind(Contracts\CartDriver::class, config('simple-commerce.cart.driver'));
        }

        return $this;
    }

    protected function bootStacheStores()
    {
        if (SimpleCommerce::isUsingStandardTaxEngine()) {
            $taxCategoryStore = new Tax\Standard\Stache\TaxCategory\TaxCategoryStore;
            $taxCategoryStore->directory(base_path('content/simple-commerce/tax-categories'));

            $taxRateStore = new Tax\Standard\Stache\TaxRate\TaxRateStore;
            $taxRateStore->directory(base_path('content/simple-commerce/tax-rates'));

            $taxZoneStore = new Tax\Standard\Stache\TaxZone\TaxZoneStore;
            $taxZoneStore->directory(base_path('content/simple-commerce/tax-zones'));

            app(Stache::class)->registerStore($taxCategoryStore);
            app(Stache::class)->registerStore($taxRateStore);
            app(Stache::class)->registerStore($taxZoneStore);
        }

        return $this;
    }

    protected function createNavItems()
    {
        if (SimpleCommerce::isUsingStandardTaxEngine()) {
            Nav::extend(function ($nav) {
                $nav->create(__('Tax Rates'))
                    ->section(__('Simple Commerce'))
                    ->route('simple-commerce.tax-rates.index')
                    ->can('view tax rates');

                $nav->create(__('Tax Categories'))
                    ->section(__('Simple Commerce'))
                    ->route('simple-commerce.tax-categories.index')
                    ->can('view tax categories')
                    ->icon('tags');

                $nav->create(__('Tax Zones'))
                    ->section(__('Simple Commerce'))
                    ->route('simple-commerce.tax-zones.index')
                    ->can('view tax zones');
            });
        }

        return $this;
    }

    protected function registerPermissions()
    {
        if (SimpleCommerce::isUsingStandardTaxEngine()) {
            Permission::register('view tax rates', function ($permission) {
                $permission->children([
                    Permission::make('edit tax rates')->children([
                        Permission::make('create tax rates'),
                        Permission::make('delete tax rates'),
                    ]),
                ]);
            });

            Permission::register('view tax categories', function ($permission) {
                $permission->children([
                    Permission::make('edit tax categories')->children([
                        Permission::make('create tax categories'),
                        Permission::make('delete tax categories'),
                    ]),
                ]);
            });

            Permission::register('view tax zones', function ($permission) {
                $permission->children([
                    Permission::make('edit tax zones')->children([
                        Permission::make('create tax zones'),
                        Permission::make('delete tax zones'),
                    ]),
                ]);
            });
        }

        return $this;
    }
}
