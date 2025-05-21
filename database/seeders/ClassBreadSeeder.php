<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;

class ClassBreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create DataType
        $dataType = $this->dataType('slug', 'classes');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'classes',
                'display_name_singular' => 'Class',
                'display_name_plural'   => 'Classes',
                'icon'                  => 'voyager-book',
                'model_name'            => 'App\\Models\\ClassRoom',
                'policy_name'           => null,
                'controller'            => 'TCG\\Voyager\\Http\\Controllers\\VoyagerBaseController',
                'generate_permissions'  => 1,
                'description'           => 'School Classes',
                'server_side'           => 0,
            ])->save();
        }

        // Create DataRows
        $dataTypeId = $dataType->id;
        $this->addDataRow($dataTypeId, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, '{}');
        $this->addDataRow($dataTypeId, 'name', 'text', 'Name', 2, 1, 1, 1, 1, 1, '{"validation":{"rule":"required"}}');
        $this->addDataRow($dataTypeId, 'numeric_value', 'number', 'Grade Level', 3, 1, 1, 1, 1, 1, '{"validation":{"rule":"nullable|numeric"}}');
        $this->addDataRow($dataTypeId, 'description', 'text_area', 'Description', 4, 0, 1, 1, 1, 1, '{"validation":{"rule":"nullable"}}');
        $this->addDataRow($dataTypeId, 'school_id', 'number', 'School ID', 5, 1, 1, 1, 1, 1, '{"validation":{"rule":"required"}}');
        $this->addDataRow($dataTypeId, 'created_at', 'timestamp', 'Created At', 6, 0, 0, 0, 0, 0, '{}');
        $this->addDataRow($dataTypeId, 'updated_at', 'timestamp', 'Updated At', 7, 0, 0, 0, 0, 0, '{}');
        $this->addDataRow($dataTypeId, 'deleted_at', 'timestamp', 'Deleted At', 8, 0, 0, 0, 0, 0, '{}');

        // Create Permissions
        Permission::generateFor('classes');

        // Ensure menu item exists with proper routes
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // Find Academic menu group
        $academicGroup = MenuItem::where('title', 'Academic')
            ->where('menu_id', $menu->id)
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

        // Check if Classes menu item exists
        $menuItem = MenuItem::where('title', 'Classes')
            ->where('menu_id', $menu->id)
            ->first();

        if (!$menuItem) {
            // Create Classes menu item if it doesn't exist
            MenuItem::create([
                'menu_id' => $menu->id,
                'title' => 'Classes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-book',
                'color' => null,
                'parent_id' => $academicGroup->id,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
                'route' => 'voyager.classes.index',
                'parameters' => null,
            ]);
        } else {
            // Update existing menu item with correct route
            $menuItem->update([
                'parent_id' => $academicGroup->id,
                'order' => 3,
                'url' => '',
                'route' => 'voyager.classes.index',
                'icon_class' => 'voyager-book',
            ]);
        }
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }

    /**
     * Add a DataRow to a DataType.
     *
     * @param int $dataTypeId
     * @param string $field
     * @param string $type
     * @param string $displayName
     * @param int $order
     * @param int $required
     * @param int $browse
     * @param int $read
     * @param int $edit
     * @param int $add
     * @param string $details
     * @return void
     */
    protected function addDataRow($dataTypeId, $field, $type, $displayName, $order, $required, $browse, $read, $edit, $add, $details)
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
                'details' => $details,
            ])->save();
        }
    }
}
