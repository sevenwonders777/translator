<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->search;

        $query = Language::orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where('name', 'LIKE', $like);
        }

        return $query->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateStudentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:languages,name',
            'priority' => 'required|integer'
        ]);
        
        $language = new Language();

        $language->name = $request->get('name');
        $language->code = str_slug($request->get('name'));
        $language->priority = $request->get('priority');
        $language->save();

        return response($language, Response::HTTP_CREATED);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Language::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        
        $this->validate($request, [
            'name' => 'required|unique:languages,name,'.$language->id,
            'priority' => 'required|integer'
        ]);

        $language->name = $request->get('name');
        //$language->code = str_slug($request->get('name'));
        $language->priority = $request->get('priority');
        $language->save();

        return response($language, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Language::destroy($id);
    }
}
