<?php

namespace App\Http\Controllers;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use App\Models\User;
use App\Models\UserRole;

class CustomMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Add custom menu items to the Voyager dashboard
     */
    public function addRoleManagementMenu()
    {
        // Check if the menu item already exists
        $menuItem = \TCG\Voyager\Models\MenuItem::where('route', 'admin.manage-roles')->first();

        if (!$menuItem) {
            // Get the admin menu
            $menu = \TCG\Voyager\Models\Menu::where('name', 'admin')->firstOrFail();

            // Create the parent menu item
            $menuItem = new \TCG\Voyager\Models\MenuItem();
            $menuItem->menu_id = $menu->id;
            $menuItem->title = 'Role Management';
            $menuItem->url = '';
            $menuItem->route = 'admin.manage-roles';
            $menuItem->target = '_self';
            $menuItem->icon_class = 'voyager-people';
            $menuItem->color = null;
            $menuItem->parent_id = null;
            $menuItem->order = 5;
            $menuItem->save();
        }

        return redirect()->route('voyager.dashboard')
            ->with('message', 'Role Management menu added successfully.');
    }
}
