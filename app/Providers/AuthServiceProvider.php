<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
    $panelSwitch
        ->modalWidth('sm')
        ->slideOver()
        ->visible(fn (): bool => auth()->user()?->hasAnyRole([
            'super_admin',
        ]));
});
    }


}
