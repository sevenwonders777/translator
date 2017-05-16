<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Language;
use App\Models\Phrase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageController extends Controller
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

        $query = Page::orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where('title', 'LIKE', $like);
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
            'title' => 'required',
        ]);
        
        $page = new Page();

        $page->title = $request->get('title');
        $page->slug = str_slug($request->get('title'));
        $page->save();

        return response($page, Response::HTTP_CREATED);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        
        $languages = Language::select(array('name', 'code'))
            ->orderBy('priority')
            ->orderBy('name')->get();
        $page->languages = $languages;
        $page->phrases = $page->getPhraseList();
        
        return $page;
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
        $page = Page::findOrFail($id);
        
        $this->validate($request, [
            'title' => 'required',
        ]);

        $page->title = $request->get('title');
        $page->slug = str_slug($request->get('title'));
        $page->save();

        return response($page, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::destroy($id);
    }
    
    public function saveRow(Request $request, $id)
    {
        $phrases = $request->get('word');
        
        foreach($phrases as $hash => $phrase) {
            foreach($phrase as $language_code => $value) {
                if(trim($value) == '') {
                    continue;
                }
                
                $language = Language::whereCode($language_code)->first();
                
                // check row is exist
                $row = Phrase::wherePageId($id)
                    ->whereLanguageId($language->id)
                    ->whereHash($hash)
                    ->first();
                if(!isset($row->id)) {
                    $row = new Phrase;
                }
                
                $row->word = $value;
                $row->page_id = $id;
                $row->language_id = $language->id;
                $row->hash = $hash;
                $row->save();
            }    
        }
        
        return response(array('result' => 'success'));
    }
    
    public function removeRow(Request $request, $id)
    {
        $hash = $request->get('hash');
        Phrase::whereHash($hash)->delete();
        
        return response(array('result' => 'success'));
    }
}
