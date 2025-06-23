<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Events\Dispatcher;
use TCG\Voyager\Facades\Voyager;
use App\VoyagerWidgets\NoticesWidget;
use App\VoyagerWidgets\StudentsWidget;
use App\VoyagerWidgets\TeachersWidget;
use App\Http\Controllers\VoyagerExtendedController;

class VoyagerExtendServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom widgets are registered in config/voyager.php

        // Hook into Voyager's events
        $this->addVoyagerHooks();

        // Override specific Voyager controllers
        $this->overrideVoyagerControllers();

        // Add custom form fields
        $this->addFormFields();
    }

    /**
     * Add hooks to Voyager's event system
     */
    private function addVoyagerHooks()
    {
        $events = app(Dispatcher::class);

        // When a new user is added through Voyager's interface
        $events->listen('voyager.users.created', function ($user) {
            // Add default related models based on role
            if ($user->role === 'teacher') {
                \App\Models\Teacher::create([
                    'user_id' => $user->id,
                    'first_name' => '',
                    'last_name' => '',
                ]);
            } elseif ($user->role === 'student') {
                \App\Models\Student::create([
                    'user_id' => $user->id,
                    'first_name' => '',
                    'last_name' => '',
                ]);
            } elseif ($user->role === 'parent') {
                \App\Models\ParentModel::create([
                    'user_id' => $user->id,
                    'first_name' => '',
                    'last_name' => '',
                ]);
            }
        });

        // After a user logs in
        $events->listen('voyager.users.login', function ($user) {
            // Update login timestamp
            $user->last_login_at = now();
            $user->last_login_ip = request()->ip();
            $user->save();
        });

        // Hook into menu display
        $events->listen('voyager.menu.display', function ($menu) {
            if ($menu->name === 'admin') {
                $menuItem = $menu->items->where('title', 'Users')->first();
                if ($menuItem) {
                    $menuItem->title = 'School Members';
                }
            }
            return $menu;
        });
    }

    /**
     * Override specific Voyager controllers
     */
    private function overrideVoyagerControllers()
    {
        // Extend or override the BREAD controller for specific models
        app()->bind(\TCG\Voyager\Http\Controllers\VoyagerBaseController::class, function () {
            return app(VoyagerExtendedController::class);
        });
    }

    /**
     * Add custom form fields
     */
    private function addFormFields()
    {
        Voyager::addFormField(\App\FormFields\RoleSelectFormField::class);
    }
}
