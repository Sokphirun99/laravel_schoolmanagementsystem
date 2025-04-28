<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserManagementMenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if menus table exists (voyager is installed)
        if (!Schema::hasTable('menus')) {
            $this->command->error('Voyager tables not found. Run voyager:install first.');
            return;
        }

        // Turn off FK constraints to avoid any potential issues with references
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Find or create the admin menu
            $menu = Menu::firstOrNew(['name' => 'admin']);

            if (!$menu->exists) {
                $this->command->info('Creating admin menu...');
                $menuData = ['name' => 'admin'];

                // Only add display_name if the column exists
                if (Schema::hasColumn('menus', 'display_name')) {
                    $menuData['display_name'] = 'Admin Menu';
                }

                $menu->fill($menuData)->save();
            }

            // Create or update User Management parent menu item
            $userManagementItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title' => 'User Management',
                'url' => '',
            ]);

            if (!$userManagementItem->exists) {
                $userManagementItem->fill([
                    'target' => '_self',
                    'icon_class' => 'voyager-people',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 2,
                    'route' => null,
                ])->save();

                $this->command->info('Created User Management menu item');
            }

            // Create or update Students menu item
            $this->createOrUpdateMenuItem(
                $menu->id,
                'Students',
                '/admin/students',
                'voyager-person',
                $userManagementItem->id,
                1
            );

            // Create or update Teachers menu item
            $this->createOrUpdateMenuItem(
                $menu->id,
                'Teachers',
                '/admin/teachers',
                'voyager-study',
                $userManagementItem->id,
                2
            );

            // Create or update Parents menu item
            $this->createOrUpdateMenuItem(
                $menu->id,
                'Parents',
                '/admin/parents',
                'voyager-people',
                $userManagementItem->id,
                3
            );

            // Create or update Users (admin/system users) menu item
            $this->createOrUpdateMenuItem(
                $menu->id,
                'System Users',
                '/admin/users',
                'voyager-person',
                $userManagementItem->id,
                4
            );

            // Create or update Roles menu item
            $this->createOrUpdateMenuItem(
                $menu->id,
                'Roles',
                '/admin/roles',
                'voyager-lock',
                $userManagementItem->id,
                5
            );

            $this->command->info('User Management menu items created successfully!');

        } catch (\Exception $e) {
            $this->command->error('Error creating menu items: ' . $e->getMessage());
        }

        // Turn FK checks back on
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Create or update a menu item
     *
     * @param int $menuId
     * @param string $title
     * @param string $url
     * @param string $iconClass
     * @param int|null $parentId
     * @param int $order
     * @return void
     */
    private function createOrUpdateMenuItem($menuId, $title, $url, $iconClass, $parentId, $order)
    {
        try {
            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menuId,
                'title' => $title,
                'url' => $url
            ]);

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target' => '_self',
                    'icon_class' => $iconClass,
                    'color' => null,
                    'parent_id' => $parentId,
                    'order' => $order,
                    'route' => null,
                ])->save();

                $this->command->info("Created {$title} menu item");
            }
        } catch (\Exception $e) {
            $this->command->error("Error creating {$title} menu item: " . $e->getMessage());
        }
    }
}
