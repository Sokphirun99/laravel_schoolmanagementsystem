<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class RoleSelectFormField extends AbstractHandler
{
    protected $codename = 'role_select';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.role_select', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'roles' => \App\Models\User::availableRoles(),
        ]);
    }
}
