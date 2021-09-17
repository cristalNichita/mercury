<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\User\Entities\Document;
use Modules\User\Entities\DocumentRequest;
use Modules\User\Http\Requests\DocumentAddRequest;

class ApiDocumentController extends BaseController
{

    public function index(Request $request, $type)
    {
        /** todo: добавить фильтрацию по типам документов $type когда станет понятно как это делать */

        $user = User::find(auth()->user()->id);
        return $user->documents;
    }

    public function request(DocumentAddRequest $request)
    {
        $validated = $request->validated();
        $documentRequest = new DocumentRequest();
        $documentRequest->type_id = $validated['type_id'];
        $documentRequest->status_id = DocumentRequest::STATUS_ID_NEW;
        $documentRequest->user_id = auth()->user()->id;
        $documentRequest->save();
        return $this->sendSuccess($documentRequest);
    }

    public function show(Document $document)
    {
        return $this->sendSuccess([
            'document' => $document,
            'url' => $document->getUrl()
        ]);
    }
}
