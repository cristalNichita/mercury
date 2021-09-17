<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Catalog\Entities\Product;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\CartItem;
use Modules\Order\Helpers\CartHelper;
use Modules\Order\Http\Requests\CartAddRequest;
use Modules\Order\Http\Requests\CartChangeRequest;
use Modules\Order\Http\Requests\CartClearRequest;
use Modules\Order\Http\Requests\CartDeleteRequest;
use Modules\Order\Http\Requests\CartIndexRequest;
use Modules\Order\Http\Requests\CartRemoveRequest;
use Modules\Order\Transformers\CartResource;

class ApiCartController extends BaseController
{

    public function findOrCreateCart($cart_id)
    {

        $cart = Cart::find($cart_id);

        $user = auth('sanctum')->user();

        if (empty($cart)) {
            $fill = ($user) ? ['user_id' => $user->id] : [];
            $cart = Cart::create($fill);
        }

        if ($user && !$cart->user_id && $user->id != $cart->user_id) {
            $cart->update(['user_id' => $user->id]);
        }

        return $cart;
    }

    /**
     * Получение корзины
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $this->findOrCreateCart($request->input('cart_id'));
    }

    /**
     * Добавление элемента в корзину
     *
     * @param CartAddRequest $request
     * @return array
     */
    public function add(CartAddRequest $request)
    {

        $cart = $this->findOrCreateCart($request->cart_id);
        $product = Product::findOrFail($request->product_id);
        if (empty($product->quantity)) {
            throw new \Exception('Продукт отсутсвует на складе');
        }
        $count = max($request->count, 1);

        $cartHelper = new CartHelper($cart);
        $message = $cartHelper->add($product, $count);

        return [
            'cart' => $cart->refresh(),
            'result' => $message
        ];
    }

    /**
     * Изменение элемента в корзине
     *
     * @param CartChangeRequest $request
     * @return array
     */
    public function change(CartChangeRequest $request)
    {
        $cart = Cart::findOrFail($request->cart_id);
        $cartHelper = new CartHelper($cart);

        $result = $cartHelper->change($request->item_id, $request->count);

        return [
            'cart' => $cart->refresh(),
            'result' => $result
        ];
    }

    /**
     * Удаление элемента из корзины
     *
     * @param CartRemoveRequest $request
     * @return array
     */
    public function remove(CartRemoveRequest $request)
    {
        $cart = Cart::findOrFail($request->cart_id);
        CartItem::destroy($request->items);
        return [
            'cart' => $cart,
            'result' => true
        ];
    }

    /**
     * Удаление всех элементов из корзины
     *
     * @param CartClearRequest $request
     * @return array
     */
    public function clear(CartClearRequest $request)
    {
        $cart = Cart::findOrFail($request->cart_id);
        $cart->items()->delete();
        return [
            'cart' => $cart,
            'result' => true
        ];
    }

    /**
     * Повторяющаяся логика, не сообразил, куда лучше её вынести, решил оставить здесь
     *
     * @param $request
     * @param null $cartId
     * @return Cart
     * @deprecated
     */
    protected function getCart($request, $cartId = null)
    {
        $user = $request->user();

        if ($user) {
            $cart = $user->cart ? $user->cart : Cart::create(['user_id' => $user->id]);
        } else {
            $cart = $cartId ? Cart::findOrFail($cartId) : Cart::create();
        }

        return $cart;
    }

    /** @deprecated */
    protected function responseArrayBuilder(CartHelper $cartHelper, $subdata = [])
    {
        $cart = $cartHelper->calculateTotalPrice();
        return array_merge([
            'cart_id' => $cart->id,
            'total_price' => $cart->total_price,
            'total_quantity' => $cartHelper->gerQuantity(),
            'messages' => $cartHelper->getMessages()
        ], $subdata);
    }
}
