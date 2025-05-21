<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\Permission;
use Illuminate\Support\Facades\DB;

class RegisterClassBread extends Command
{
    protected $signature = 'voyager:register-class-bread';
    protected $description = 'Register the ClassRoom model with Voyager BREAD system';

    public function handle()
    {
        $this->info('Registering ClassRoom model with Voyager BREAD system...');

        // Create DataType
        $dataType = DataType::firstOrNew(['slug' => 'classes']);

        if (!$dataType->exists) {
            $dataType->fill([
                'name' => 'classes',
                'display_name_singular' => 'Class',
                'display_name_plural' => 'Classes',
                'icon' => 'voyager-book',
                'model_name' => 'App\\Models\\ClassRoom',
                'controller' => 'App\\Http\\Controllers\\ClassRoomController',
                'generate_permissions' => 1,
                'description' => 'School classes',
                'server_side' => 0,
            ])->save();

            $this->info('Created data type for ClassRoom model');
        } else {
            $dataType->update([
                'model_name' => 'App\\Models\\ClassRoom',
                'controller' => 'App\\Http\\Controllers\\ClassRoomController',
                'icon' => 'voyager-book',
            ]);

            $this->info('Updated data type for ClassRoom model');
        }

        // Create DataRows
        $dataTypeId = $dataType->id;

        $this->createDataRow($dataTypeId, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, '{"validation":{"rule":"required"}}');
        $this->createDataRow($dataTypeId, 'name', 'text', 'Name', 2, 1, 1, 1, 1, 1, '{"validation":{"rule":"required|max:255"}}');
        $this->createDataRow($dataTypeId, 'numeric_value', 'number', 'Grade Level', 3, 0, 1, 1, 1, 1, '{"validation":{"rule":"nullable|numeric"}}');
        $this->createDataRow($dataTypeId, 'description', 'text_area', 'Description', 4, 0, 1, 1, 1, 1, '{"validation":{"rule":"nullable"}}');
        $this->createDataRow($dataTypeId, 'school_id', 'relationship', 'School', 5, 1, 1, 1, 1, 1, '{"validation":{"rule":"required"},"relationship":{"key":"id","label":"name","model":"App\\\\Models\\\\School"}}');
        $this->createDataRow($dataTypeId, 'created_at', 'timestamp', 'Created At', 6, 0, 1, 0, 0, 0, '{}');
        $this->createDataRow($dataTypeId, 'updated_at', 'timestamp', 'Updated At', 7, 0, 0, 0, 0, 0, '{}');
        $this->createDataRow($dataTypeId, 'deleted_at', 'timestamp', 'Deleted At', 8, 0, 0, 0, 0, 0, '{}');

        // Generate permissions
        Permission::generateFor('classes');

        $this->info('Generated permissions for classes');

        // Run the menu cleanup command to ensure proper menu structure
        $this->call('menu:cleanup');

        $this->info('ClassRoom model registered successfully with Voyager BREAD system!');
        return 0;
    }

    protected function createDataRow($dataTypeId, $field, $type, $displayName, $order, $required, $browse, $read, $edit, $add, $details)
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

            $this->info("Created data row for field '{$field}'");
        } else {
            $dataRow->update([
                'type' => $type,
                'display_name' => $displayName,
                'required' => $required,
                'browse' => $browse,
                'read' => $read,
                'edit' => $edit,
                'add' => $add,
                'details' => $details,
            ]);

            $this->info("Updated data row for field '{$field}'");
        }
    }
}
