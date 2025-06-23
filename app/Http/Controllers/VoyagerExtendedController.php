<?php

namespace App\Http\Controllers;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataRestored;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class VoyagerExtendedController extends VoyagerBaseController
{
    use BreadRelationshipParser;

    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Extended Browse our Data Type (B)READ
    //
    //****************************************

    public function index(Request $request)
    {
        // Call parent method to get standard functionality
        $response = parent::index($request);
        
        // Perform any additional custom logic for specific models
        $slug = $this->getSlug($request);
        
        switch ($slug) {
            case 'notices':
                // Add any custom data here for notices
                $response->with('custom_data', 'Custom data for notices');
                break;
            case 'students':
                // Add any custom data here for students
                break;
            case 'teachers':
                // Add any custom data here for teachers
                break;
            default:
                // Do nothing for other models
                break;
        }
        
        return $response;
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //  Extended Read one item of our Data Type B(R)EAD
    //
    //****************************************
    
    public function show(Request $request, $id)
    {
        // Call parent method to get standard functionality
        $response = parent::show($request, $id);
        
        // Perform any additional custom logic for specific models
        $slug = $this->getSlug($request);
        
        switch ($slug) {
            case 'notices':
                // Add any custom data here for a notice
                break;
            case 'students':
                // Add any custom data here for a student
                break;
            case 'teachers':
                // Add any custom data here for a teacher
                break;
            default:
                // Do nothing for other models
                break;
        }
        
        return $response;
    }

    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Extended Edit an item of our Data Type BR(E)AD
    //
    //****************************************
    
    public function edit(Request $request, $id)
    {
        // Call parent method to get standard functionality
        $response = parent::edit($request, $id);
        
        // Perform any additional custom logic for specific models
        $slug = $this->getSlug($request);
        
        switch ($slug) {
            case 'notices':
                // Add any custom data here for editing a notice
                break;
            case 'students':
                // Add any custom data here for editing a student
                break;
            case 'teachers':
                // Add any custom data here for editing a teacher
                break;
            default:
                // Do nothing for other models
                break;
        }
        
        return $response;
    }

    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Extended Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function store(Request $request)
    {
        // Call parent method to get standard functionality
        $response = parent::store($request);
        
        // Perform any additional custom logic for specific models
        $slug = $this->getSlug($request);
        
        switch ($slug) {
            case 'notices':
                // Add any custom data here after creating a notice
                break;
            case 'students':
                // Add any custom data here after creating a student
                break;
            case 'teachers':
                // Add any custom data here after creating a teacher
                break;
            default:
                // Do nothing for other models
                break;
        }
        
        return $response;
    }
}
