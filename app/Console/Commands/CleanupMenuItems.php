<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupMenuItems extends Command
{
    protected $signature = 'menu:cleanup';
    protected $description = 'Cleanup and reorganize admin menu items';

    // Menu IDs and their order
    protected $coreMenuItems = [
        'Dashboard' => 1,
    ];

    protected $userManagementGroup = [
        'title' => 'User Management',
        'order' => 2,
        'icon' => 'voyager-people',
        'children' => [
            'Users' => 1,
            'Roles' => 2,
            'User Roles' => 3,
        ]
    ];

    protected $academicGroup = [
        'title' => 'Academic',
        'order' => 3,
        'icon' => 'voyager-study',
        'children' => [
            'Schools' => 1,
            'Departments' => 2,
            'Classes' => 3,
            'Subjects' => 4,
            'Academic Years' => 5,
        ]
    ];

    protected $peopleGroup = [
        'title' => 'People',
        'order' => 4,
        'icon' => 'voyager-people',
        'children' => [
            'Students' => 1,
            'Teachers' => 2,
            'Parents' => 3,
        ]
    ];

    protected $libraryGroup = [
        'title' => 'Library',
        'order' => 5,
        'icon' => 'voyager-book',
        'children' => [
            'Books' => 1,
            'Book Categories' => 2,
            'Book Issues' => 3,
            'Library Books' => 4,
        ]
    ];

    protected $contentGroup = [
        'title' => 'Content',
        'order' => 6,
        'icon' => 'voyager-file-text',
        'children' => [
            'Pages' => 1,
            'Posts' => 2,
            'Categories' => 3,
            'Media' => 4,
        ]
    ];

    protected $systemGroup = [
        'title' => 'System',
        'order' => 7,
        'icon' => 'voyager-tools',
        'children' => [
            'Tools' => 1,
            'Settings' => 2,
        ]
    ];

    // Items to be removed
    protected $removeItems = [
        'Role Demo'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Cleaning up and reorganizing menu items...');

        // Get the admin menu ID
        $adminMenu = DB::table('menus')->where('name', 'admin')->first();
        if (!$adminMenu) {
            $this->error('Admin menu not found!');
            return 1;
        }

        $menuId = $adminMenu->id;

        // Step 1: Remove unwanted menu items
        foreach ($this->removeItems as $item) {
            DB::table('menu_items')
                ->where('menu_id', $menuId)
                ->where('title', $item)
                ->delete();

            $this->info("Removed menu item: $item");
        }

        // Step 2: Update core menu items
        foreach ($this->coreMenuItems as $title => $order) {
            DB::table('menu_items')
                ->where('menu_id', $menuId)
                ->where('title', $title)
                ->update([
                    'order' => $order,
                    'parent_id' => null
                ]);

            $this->info("Updated core menu item: $title with order $order");
        }

        // Step 3: Create/update menu groups and organize items
        $this->processMenuGroup($menuId, $this->userManagementGroup);
        $this->processMenuGroup($menuId, $this->academicGroup);
        $this->processMenuGroup($menuId, $this->peopleGroup);
        $this->processMenuGroup($menuId, $this->libraryGroup);
        $this->processMenuGroup($menuId, $this->contentGroup);
        $this->processMenuGroup($menuId, $this->systemGroup);

        // Step 4: Clean up items not categorized
        $this->handleToolsSubmenu($menuId);

        // Step 5: Set proper routes for menu items
        $this->setMenuItemRoutes($menuId);

        $this->info('Menu items cleanup completed successfully!');
        $this->info('Please refresh your browser to see the changes.');
        return 0;
    }

    private function processMenuGroup($menuId, $groupConfig)
    {
        // Check if group already exists
        $groupItem = DB::table('menu_items')
            ->where('menu_id', $menuId)
            ->where('title', $groupConfig['title'])
            ->first();

        // Create group if it doesn't exist
        if (!$groupItem) {
            $groupItemId = DB::table('menu_items')->insertGetId([
                'menu_id' => $menuId,
                'title' => $groupConfig['title'],
                'url' => '',
                'target' => '_self',
                'icon_class' => $groupConfig['icon'],
                'color' => null,
                'parent_id' => null,
                'order' => $groupConfig['order'],
                'created_at' => now(),
                'updated_at' => now(),
                'route' => null,
                'parameters' => null,
            ]);

            $this->info("Created menu group: {$groupConfig['title']} with ID $groupItemId");
        } else {
            $groupItemId = $groupItem->id;

            // Update the group properties
            DB::table('menu_items')
                ->where('id', $groupItemId)
                ->update([
                    'icon_class' => $groupConfig['icon'],
                    'order' => $groupConfig['order'],
                    'updated_at' => now(),
                ]);

            $this->info("Updated menu group: {$groupConfig['title']}");
        }

        // Process children
        foreach ($groupConfig['children'] as $title => $order) {
            $menuItem = DB::table('menu_items')
                ->where('menu_id', $menuId)
                ->where('title', $title)
                ->first();

            if ($menuItem) {
                DB::table('menu_items')
                    ->where('id', $menuItem->id)
                    ->update([
                        'parent_id' => $groupItemId,
                        'order' => $order,
                        'updated_at' => now(),
                    ]);

                $this->info("  Added '$title' to '{$groupConfig['title']}' group with order $order");
            } else {
                // Default icon based on group
                $defaultIcon = 'voyager-data';
                switch ($groupConfig['title']) {
                    case 'Academic':
                        $defaultIcon = 'voyager-book';
                        break;
                    case 'People':
                        $defaultIcon = 'voyager-person';
                        break;
                    case 'Library':
                        $defaultIcon = 'voyager-book';
                        break;
                    case 'Content':
                        $defaultIcon = 'voyager-file-text';
                        break;
                    case 'System':
                        $defaultIcon = 'voyager-settings';
                        break;
                }

                // Create the menu item if it doesn't exist
                $itemId = DB::table('menu_items')->insertGetId([
                    'menu_id' => $menuId,
                    'title' => $title,
                    'url' => '', // Empty URL, will need to be set manually later
                    'target' => '_self',
                    'icon_class' => $defaultIcon,
                    'color' => null,
                    'parent_id' => $groupItemId,
                    'order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'route' => null,
                    'parameters' => null,
                ]);

                $this->info("  Created new menu item '$title' in '{$groupConfig['title']}' group with order $order");
            }
        }
    }

    private function handleToolsSubmenu($menuId)
    {
        // Find the Tools menu item
        $toolsItem = DB::table('menu_items')
            ->where('menu_id', $menuId)
            ->where('title', 'Tools')
            ->first();

        // Find the System menu item
        $systemItem = DB::table('menu_items')
            ->where('menu_id', $menuId)
            ->where('title', 'System')
            ->first();

        if ($toolsItem && $systemItem) {
            // Move all Tools submenu items to be under System
            DB::table('menu_items')
                ->where('parent_id', $toolsItem->id)
                ->update([
                    'parent_id' => $systemItem->id,
                ]);

            $this->info("Moved Tools submenu items to System group");
        }
    }

    /**
     * Set the correct routes for specific menu items
     */
    private function setMenuItemRoutes($menuId)
    {
        // Define mapping between menu item titles and their corresponding routes and icons
        $menuItemConfig = [
            'Classes' => [
                'route' => 'voyager.classes.index',
                'icon' => 'voyager-book'
            ],
            'Students' => [
                'route' => 'voyager.students.index',
                'icon' => 'voyager-people'
            ],
            'Teachers' => [
                'route' => 'voyager.teachers.index',
                'icon' => 'voyager-study'
            ],
            'Parents' => [
                'route' => 'voyager.parents.index',
                'icon' => 'voyager-people'
            ],
            'Subjects' => [
                'route' => 'voyager.subjects.index',
                'icon' => 'voyager-book'
            ],
            'Schools' => [
                'route' => 'voyager.schools.index',
                'icon' => 'voyager-home'
            ],
            'Departments' => [
                'route' => 'voyager.departments.index',
                'icon' => 'voyager-categories'
            ],
            'Academic Years' => [
                'route' => 'voyager.academic-years.index',
                'icon' => 'voyager-calendar'
            ],
            'Books' => [
                'route' => 'voyager.books.index',
                'icon' => 'voyager-book'
            ],
            'Book Categories' => [
                'route' => 'voyager.book-categories.index',
                'icon' => 'voyager-categories'
            ],
            'Book Issues' => [
                'route' => 'voyager.book-issues.index',
                'icon' => 'voyager-documentation'
            ],
            'Library Books' => [
                'route' => 'voyager.library-books.index',
                'icon' => 'voyager-book'
            ],
        ];

        foreach ($menuItemConfig as $title => $config) {
            // Find the menu item
            $menuItem = DB::table('menu_items')
                ->where('menu_id', $menuId)
                ->where('title', $title)
                ->first();

            if ($menuItem) {
                // Update the route and icon
                DB::table('menu_items')
                    ->where('id', $menuItem->id)
                    ->update([
                        'route' => $config['route'],
                        'url' => '', // Empty string instead of null
                        'icon_class' => $config['icon'],
                        'updated_at' => now(),
                    ]);

                $this->info("Set route '{$config['route']}' and icon '{$config['icon']}' for menu item '$title'");
            }
        }
    }
}
