<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\User\Entities\Contact;
use Modules\User\Entities\ContactParam;
use Modules\User\Http\Requests\ApiUserAddressRequest;
use function PHPUnit\Framework\throwException;


class ApiUserAddressController extends BaseController
{
    public function index(Request $request)
    {
        /** @var Contact $contact */
        $contact = $request->user()->contact;
        return $contact->params()
            ->select(['id', 'view', 'value'])
            ->where('type', 'address')
            ->orderBy('id', 'desc')->get();
    }

    public function store(ApiUserAddressRequest $request)
    {
        /** @var Contact $contact */
        $contact = $request->user()->contact;

        return $contact->params()->create([
            'type' => 'address',
            'view' => $request->view,
            'value' => $request->value
        ])->only('view', 'value', 'id');
    }

    protected function checkAccess($request, $address)
    {
        //TODO: Определить почему у пользователей получаются одинаковые контакты
        return;
        // Проверяем принадлежность пользователю
        if ($address->parent->user->id !== $request->user()->id) {
            throw new \Exception('Forbidden', 403);
        }
    }

    public function destroy(Request $request, ContactParam $address)
    {
        $this->checkAccess($request, $address);

        $address->delete();

        return 'success';
    }

    public function update(ApiUserAddressRequest $request, ContactParam $address)
    {
        $this->checkAccess($request, $address);

        $address->update($request->only('view', 'value'));

        return $address->only(['id', 'view', 'value']);
    }

}
