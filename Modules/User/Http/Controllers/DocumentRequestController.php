<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\User\Entities\DocumentRequest;

class DocumentRequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        /** @var DocumentRequest $documents */
        $documents = DocumentRequest::sort($request->get('sort', 'id-desc'))
            ->paginate($this->per_page);
        $documents->load('user');
        return Inertia::render('Document/Index',[
            'documents' => $documents,
            'sort' => $request->input('sort'),
        ]);
    }

    public function documents(Request $request, $status = DocumentRequest::STATUSES_ALIAS[DocumentRequest::STATUS_ID_NEW])
    {
        $documents = DocumentRequest::sort($request->get('sort', 'id-desc'))
            ->where('status_id', DocumentRequest::STATUSES_ALIAS[$status])
            ->paginate($this->per_page);
        $documents->load('user');
        return Inertia::render('Document/Index',[
            'documents' => $documents,
            'sort' => $request->input('sort'),
        ]);
    }

    /**
     * @param DocumentRequest $document
     * @return \Inertia\Response
     */
    public function show(DocumentRequest $document)
    {
        return Inertia::render('Document/Show', [
            'document'     => $document->load('user'),
            'statuses'      => DocumentRequest::getStatusNamedArr(),
            'type'          => DocumentRequest::TYPES[$document->type_id]
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, DocumentRequest $document)
    {
        $data = $request->input();
        $document->update($data);
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('document::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
