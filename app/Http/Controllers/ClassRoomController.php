<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class ClassRoomController extends VoyagerBaseController
{
    // This controller extends VoyagerBaseController which has all the necessary BREAD methods
    // We can add additional custom functionality if needed

    /**
     * Display a listing of the classes.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Add any custom logic before calling parent method
        return parent::index($request);
    }

    /**
     * Show form for creating a new class.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return parent::create($request);
    }

    /**
     * Store a newly created class in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return parent::store($request);
    }

    /**
     * Display the specified class.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return parent::show($request, $id);
    }

    /**
     * Show the form for editing the specified class.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return parent::edit($request, $id);
    }

    /**
     * Update the specified class in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }

    /**
     * Remove the specified class from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return parent::destroy($request, $id);
    }
}
