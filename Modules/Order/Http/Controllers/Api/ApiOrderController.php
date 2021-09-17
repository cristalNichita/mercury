<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Complaint\Http\Requests\ComplaintIndexRequest;
use Modules\Order\Classes\OrderWorkflow;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\Delivery;
use Modules\Order\Entities\Order;
use Modules\Order\Helpers\CartHelper;
use Modules\Order\Http\Requests\OrderCreateRequest;
use Modules\Order\Transformers\OrderResource;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Events\CompanyCreated;
use Modules\User\Events\ContactCreated;

class ApiOrderController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth:sanctum')->except(['create']);
    }

    public function index(Request $request)
    {
        $contact = $request->user()->contact;
        return OrderResource::collection($contact->orders ?? []);
    }

    public function show(Order $order)
    {
        abort_if($order->user_id != auth()->user()->id, 404, 'Заказ не найден');
        return new OrderResource($order);
    }

    protected function createContact($fio, $phone, $email)
    {
        $contact = Contact::create(['name' => $fio]);
        $contact->phone = $phone;
        $contact->email = $email;

        // Для выгрузки в 1С
        event(new ContactCreated($contact));

        return $contact;
    }

    protected function companyForOrder(Request $request, Contact $contact)
    {

        if ($request->input('companyType') == Company::FIS) {

            $attributes = [
                'type' => Company::FIS,
                'name' => $request->input('companyFio')
            ];

            $company = $contact->holding->companies()->where($attributes)->first();

            if (!$company) {
                $attributes['type_1c'] = 'Физическое лицо';
                $company = $contact->holding->companies()->create($attributes);
                event(new CompanyCreated($company));
            }
        } else {
            $attributes = [
                'type' => Company::URI,
                'inn' => $request->input('companyInn')
            ];

            $company = $contact->holding->companies()->where($attributes)->first();

            $fill = [
                'type' => Company::URI,
                'inn' => $request->input('companyInn'),
                'kpp' => $request->input('companyKpp'),
                'name' => $request->input('companyName'),
            ];

            if ($request->input('paymentType') === 'invoice') {
                $fill['bank_name'] = $request->input('companyBank');
                $fill['bank_bik'] = $request->input('companyBik');
                $fill['bank_invoice'] = $request->input('companySchet');
            }

            if (!$company) {

                $fill['type_1c'] = 'Юридическое лицо';

                $company = $contact->holding->companies()->create($fill);
                event(new CompanyCreated($company));
            } else {
                // Todo: Тут нужно проверить на возможность редактирвоания
                $company->update($fill);
            }
        }

        return $company;
    }

    protected function contactForOrder(Request $request)
    {
        $user = auth('sanctum')->user();

        $phone = $request->input('contactPhone');
        $email = $request->input('contactEmail');
        $fio = $request->input('contactFio');

        if (!$user) {

            if (User::findByPhone($phone)) {
                throw new \Exception('confirm_phone');
            }

            if (User::findByEmail($phone)) {
                throw new \Exception('confirm_email');
            }

            // Для безопасности ищем только по телефону
            $contact = Contact::findByPhone($phone);

            if (!$contact) {

                $contact = $this->createContact($fio, $phone, $email);

                // Тут же создаем пользователя и авторизуем его
                $user = User::create([
                    'name' => $fio,
                    'phone' => $phone,
                    'email' => $email,
                    'contact_id' => $contact->id
                ]);
                Auth::login($user);

            } else {
                // Если контакт найден - добавляем email и росто создаем на него заказ без авторизации
                // TODO: подумать над безопасностью, тут я могу ввести телефон интересующей меня компании
                // TODO: и свой email - далее авторизуюсь с помощью email и получаю доступ ко всем заказам компании
                $contact->email = $email;
            }

        } else {

            // Крайний случай - так как контакт должен уже быть
            if (!$user->contact) {
                $contact = $this->createContact($fio, $phone, $email);
                $user->contact()->assign($contact);
            }

            $contact = $user->contact;

            $contact->phone = $phone;
            $contact->email = $email;
        }

        return $contact;
    }

    public function create(Request $request)
    {
        try {

            $contact = $this->contactForOrder($request);

            $company = $this->companyForOrder($request, $contact);

            $order = OrderWorkflow::createFromSite($request, $contact, $company);

            $order->load('payment');

            return ['order' => $order, 'user' => auth('sanctum')->user()];

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        $user = auth()->user();
        if ((!$user) || ($order->user_id != $user->id)) {
            return $this->sendError('Заказ не найден', 404);
        }

        return ['result' => OrderWorkflow::setOrderStatus($order, 'cancel')];
    }

    public function createOld(OrderCreateRequest $request, Delivery $delivery)
    {

        $validated = $request->validated();
        $user = auth()->user();

        $payment = DB::transaction(function () use ($validated, $delivery, $user) {


            $cart = Cart::where('user_id', $user->id)->with('items')->findOrFail($validated['cart_id']);

            $order = Order::create([
                'user_id' => $user->id,
                'email' => $validated['email'],
                'delivery_id' => $delivery->id,
                'order_status' => 'Новый',
                'phone_number' => $validated['phone'],
                'notes' => $validated['comment'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'need_invoice' => $validated['need_invoice'] ?? false,
                'need_call' => $validated['need_call'],
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'sum' => $item->total_price,
                    'price' => $item->price,
                    'count' => $item->quantity,
                    'product_id' => $item->product_id,
                ]);
            }


            $helper = new CartHelper($cart);
            $helper->clear();

            if (!empty($validated['is_online_pay'])) {
                return $order->createUnitellerPayment();
            }

        });

        return $this->sendSuccess($payment->createPaymentForm());


    }
}
