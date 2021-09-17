<?php

namespace Modules\Complaint\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Entities\ComplaintFile;
use Modules\Complaint\Events\ComplaintChangeStatus;
use Modules\Complaint\Events\ComplaintCreated;
use Modules\Complaint\Http\Requests\ComplaintAddRequest;
use App\Traits\FileTrait;

class ApiComplaintController extends BaseController
{

    use FileTrait;

    public function index(Request $request)
    {
        return Complaint::with('order')
            ->where('user_id','=',auth()->user()->id)
            ->sort($request->get('sort', 'id'))
            ->paginate($this->per_page);
    }

    public function add(ComplaintAddRequest $request)
    {
        $validated = $request->validated();

        $complaint = Complaint::create([
            'order_id'      => $validated['order_id'],
            'type_id'       => $validated['type_id'],
            'description'   => $validated['description'],
            'comment'       => $validated['comment'],
            'status_id'     => Complaint::STATUS_ID_INJOB,
            'user_id'       => auth()->user()->id,
        ]);
        if ($request->hasFile('files')) {
            foreach ($request['files'] as $file) {
                $fileInfoArr = $this->fileUpload($file);
                ComplaintFile::create([
                    'complaint_id' => $complaint->id,
                    'file_path'    => $fileInfoArr['filePath'],
                    'name'         => $fileInfoArr['fileOrigin']
                ]);
            }
        }

        event( new ComplaintCreated($complaint) );

        return $this->sendSuccess($complaint->load('order','order.items.product','files'));
    }

    public function cancel(Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->user()->id) {
            return $this->sendResponse(['message' => 'Недопустимая операция'], 'error', 200);
        }
        $complaint->update(['status_id' => Complaint::STATUS_ID_STOP]);

        event(new ComplaintChangeStatus($complaint));

        return $this->sendSuccess($complaint);
    }

    public function show(Complaint $complaint)
    {
        abort_if($complaint->user_id != auth()->user()->id, 404, 'Рекламация не была найдена');
        return $this->sendSuccess($complaint->load('order','order.items.product','files'));
    }
}
