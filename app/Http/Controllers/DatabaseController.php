<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{
    public function showUsers()
    {
        $columns = Schema::getColumnListing('users');
        $columnDetails = [];
        
        foreach ($columns as $column) {
            $columnDetails[$column] = DB::connection()->getDoctrineColumn('users', $column)->getType()->getName();
        }
        
        return response()->json([
            'columns' => $columns,
            'column_details' => $columnDetails
        ]);
    }
}
