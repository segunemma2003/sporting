<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use Illuminate\Http\Request;

class FixtureController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->sendResponse(Fixture::all(), "Successfully retrieved all fixtures data");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return ($fixtures = Fixture::create((new Fixture($request->all()))->toArray())) ? $this->sendResponse($fixtures, 'Successfully Created Fixture Data') : $this->sendError('failed to create ', [], 501);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return ($fixtures = Fixture::where('id', $id)->update((new Fixture($request->all()))->toArray())) ? $this->sendResponse(Fixture::where('id', $id)->first(), 'Successfully Updated Fixture Data') : $this->sendError('failed to update ', [], 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return ($fixtures = Fixture::where('id', $id)->delete()) ? $this->sendResponse([], 'Successfully Deleted Fixture Data') : $this->sendError('failed to delete ', [], 501);
    }
}
