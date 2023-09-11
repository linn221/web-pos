<?php

namespace App\Providers;

use App\Models\Stock;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use App\Observers\StockObserver;
use App\Observers\VoucherObserver;
use App\Observers\VoucherRecordObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Stock::observe(StockObserver::class);
        VoucherRecord::observe(VoucherRecordObserver::class);
        Voucher::observe(VoucherObserver::class);
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
