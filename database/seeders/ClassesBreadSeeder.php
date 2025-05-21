<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;

class ClassesBreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create DataType for classes
        $dataType = $this->dataType('name', 'classes');
        if (!$dataType->exists) {
            $dataType->fill([
                'slug' => 'classes',
                'display_name_singular' => 'Class',
                'display_name_plural' => 'Classes',
                'icon' => 'voyager-book',
                'model_name' => 'App\\Models\\ClassRoom',
                'controller' => 'App\\Http\\Controllers\\ClassRoomController',
                'generate_permissions' => 1,
                'description' => 'School Classes',
                'server_side' => 0,
            ])->save();
        }

        // Add DataRows (table columns)
        $dataTypeId = $dataType->id;

        $this->addDataRow($dataTypeId, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0);
        $this->addDataRow($dataTypeId, 'name', 'text', 'Name', 2, 1, 1, 1, 1, 1);
        $this->addDataRow($dataTypeId, 'numeric_value', 'number', 'Grade Level', 3, 0, 1, 1, 1, 1);
        $this->addDataRow($dataTypeId, 'description', 'text_area', 'Description', 4, 0, 1, 1, 1, 1);
        $this->addDataRow($dataTypeId, 'school_id', 'number', 'School ID', 5, 1, 1, 1, 1, 1);
        $this->addDataRow($dataTypeId, 'created_at', 'timestamp', 'Created At', 6, 0, 1, 0, 0, 0);
        $this->addDataRow($dataTypeId, 'updated_at', 'timestamp', 'Updated At', 7, 0, 0, 0, 0, 0);
        $this->addDataRow($dataTypeId, 'deleted_at', 'timestamp', 'Deleted At', 8, 0, 0, 0, 0, 0);

        // Generate permissions
        Permission::generateFor('classes');

        // Make sure menu item exists with proper route
        $this->ensureMenuItemExists('Classes', 'voyager.classes.index', 'voyager-book');

        $this->command->info('Classes BREAD added successfully');
    }

    /**
     * [dataType description].
     *
     * @param string $field
     * @param string $value
     *
     * @return \TCG\Voyager\Models\DataType
     */
    protected function dataType($field, $value)
    {
        return DataType::firstOrNew([$field => $value]);
    }

    /**
     * [addDataRow description].
     *
     * @param int    $dataTypeId
     * @param string $field
     * @param string $type
     * @param string $displayName
     * @param int    $order
     * @param int    $required
     * @param int    $browse
     * @param int    $read
     * @param int    $edit
     * @param int    $add
     *
     * @return void
     */
    protected function addDataRow($dataTypeId, $field, $type, $displayName, $order, $required, $browse, $read, $edit, $add)
    {
        $dataRow = DataRow::firstOrNew([
            'data_type_id' => $dataTypeId,
            'field' => $field,
        ]);

        if (!$dataRow->exists) {
            $dataRow->fill([
                'type' => $type,
                'display_name' => $displayName,
                'required' => $required,
                'browse' => $browse,
                'read' => $read,
                'edit' => $edit,
                'add' => $add,
                'delete' => 1,
                'order' => $order,
            ])->save();
        }
    }

    /**
     * Ensure menu item exists with the correct properties.
     *
     * @param string $title
     * @param string $route
     * @param string $icon
     *
     * @return void
     */
    protected function ensureMenuItemExists($title, $route, $icon)
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // Find Academic menu group
        $academicGroup = MenuItem::where('menu_id', $menu->id)
            ->where('title', 'Academic')
            ->first();

        if (!$academicGroup) {
            // Create Academic group if it doesn't exist
            $academicGroup = MenuItem::create([
                'menu_id' => $menu->id,
                'title' => 'Academic',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-study',
                'color' => null,
                'parent_id' => null,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
                'route' => null,
                'parameters' => null,
            ]);
        }

        // Check if the menu item exists
        $menuItem = MenuItem::where('menu_id', $menu->id)
            ->where('title', $title)
            ->first();

        if (!$menuItem) {
            // Create the menu item
            MenuItem::create([
                'menu_id' => $menu->id,
                'title' => $title,
                'url' => '',
                'target' => '_self',
                'icon_class' => $icon,
                'color' => null,
                'parent_id' => $academicGroup->id,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
                'route' => $route,
                'parameters' => null,
            ]);
        } else {
            // Update the menu item
            $menuItem->update([
                'parent_id' => $academicGroup->id,
                'order' => 3,
                'url' => '',
                'route' => $route,
                'icon_class' => $icon,
            ]);
        }
    }
}
