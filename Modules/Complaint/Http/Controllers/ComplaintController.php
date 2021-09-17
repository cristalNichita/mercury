<?php

namespace Modules\Complaint\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Events\ComplaintChangeStatus;

class ComplaintController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $status = null)
    {

        $status = Complaint::STATUSES_ALIAS[ $status ] ?? $status;

        /** @var Complaint $complaints */
        $complaints = Complaint::with('user')
            ->sort($request->get('sort', 'id-desc'))
            ->when($status, function($query, $status) {
                return $query->where('status_id', $status);
            })
            ->paginate($this->per_page);

        return Inertia::render('Complaint/Index',[
            'complaints' => $complaints,
            'sort' => $request->input('sort'),
        ]);
    }

    /**
     * @param Complaint $complaint
     * @return \Inertia\Response
     */
    public function show(Complaint $complaint)
    {
        return Inertia::render('Complaint/Show', [
            'complaint'     => $complaint->load('user','order','order.items.product','files'),
            'statuses'      => Complaint::getStatusNamedArr(),
            'type'          => Complaint::TYPES[$complaint->type_id]
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Complaint $complaint)
    {
        $data = $request->only(['status_id']);
        $complaint->update($data);

        event( new ComplaintChangeStatus($complaint) );

        $status = array_search($data['status_id'], Complaint::STATUSES_ALIAS);
        return redirect(route('complaints', ['status' => $status]));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

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
