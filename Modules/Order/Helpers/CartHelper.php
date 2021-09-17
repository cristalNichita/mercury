<?php


namespace Modules\Order\Helpers;


use Exception;
use http\Message;
use Illuminate\Support\Arr;
use Modules\Catalog\Entities\Product;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\CartItem;

class CartHelper
{
    // Объединение корзин для пользователя
    // объединить в корзину/с корзиной
    // Добавление в корзину
    // Изменения товара в корзине
    // Удаление из корзины
    // check intersect

    protected ?Cart $cart = null;
    protected array $messages = [];

    /**
     * CartHelper constructor.
     * @param Cart $cart
     */
    public function __construct($cart)
    {
        $this->cart = $cart;
        $this->messages = [];
    }

    /**
     * Объединяет текущую корзину с той, которая приходит в параметрах
     *
     * @param Cart $cart Корзина, с которой мы объединяем текущую
     * @return Cart
     * @throws Exception
     */
    public function merge($cart)
    {
        $items = $cart->items;
        $intersectedItems = [];

        // Проверяем есть ли совпадение товаров
        $items->each(function ($item) use (&$intersectedItems) {
            $intersected = $this->checkIntersect($item->product_id);

            if ($intersected) {
                $intersectedItems[] = $item->id;
                $this->change($intersected, $intersected->quantity + $item->quantity);
                $item->delete();
            }
        });

        // Синхронизируем элементы корзины исключая те, которые совпадают
        $items = $items->except($intersectedItems);

        CartItem::whereIn('id', $items->modelKeys())->update([
            'cart_id' => $this->cart->id
        ]);

        $cart->delete();

        $this->cart->refresh();
        return $this->calculateTotalPrice();
    }

    /**
     * Добавляет товар в корзину
     *
     * @param Product $product
     * @param int $count
     * @return bool|string
     */
    public function add($product, $count = 1)
    {
        $result = true;

        $item = $this->checkIntersect($product->id);

        if ($item) {
            $result = $this->change($item->id, $item->count + $count);
        } else {

            if ($count > $product->quantity) {
                $count = $product->quantity;
                $result = "На складе только $product->quantity шт.";
            }

            $this->cart->items()->create([
                'product_id' => $product->id,

                'name' => $product->title,
                'article' => $product->id_1c,

                'old_price' => $product->old_price,
                'price' => $product->price,

                'count' => $count,
                'total_price' => $count * $product->price,
            ]);
        }

        return $result;
    }

    /**
     * Меняет кол-во товара в корзине
     *
     * @param int $item_id
     * @param int $count
     * @return bool|string
     */
    public function change(int $item_id, int $count)
    {
        $result = true;
        /** @var CartItem $item */
        $item = $this->cart->items()->findOrFail($item_id);

        if ($count < 1) {
            $item->delete();
            return $result;
        }

        if ($count > $item->product->quantity) {
            $count = $item->product->quantity;
            $result = "К сожалению товара на складе только $count шт.";
        }

        $item->update([
            'count' => $count,
            'total_price' => $count * $item->price
        ]);

        return $result;
    }


    /**
     * Удаляет товар из корзины
     *
     * @param array $items
     * @return Cart
     */
    public function remove($items)
    {
        CartItem::destroy($items);
        $this->cart->refresh();
        return $this->calculateTotalPrice();
    }

    public function clear()
    {
        $items = $this->cart->items->modelKeys();
        return $this->remove($items);
    }

    /**
     * Метод перерассчета общей стоимости корзины
     *
     * @return Cart
     * @deprecated
     */
    public function calculateTotalPrice()
    {
        $totalPrice = $this->cart->items->sum('total_price');
        $this->cart->update(['total_price' => $totalPrice]);
        $this->cart->save();

        return $this->cart;
    }

    public function gerQuantity()
    {
        return $this->cart->items->sum('quantity');
    }

    /**
     * Проверяет наличие товара в корзине, если уже есть, то возвращает id
     *
     * @param integer $productId
     * @return CartItem|bool
     */
    public function checkIntersect(int $productId)
    {
        return $this->cart->items->firstWhere('product_id', $productId) ?? false;
    }

    /**
     * Есть ли сообщения для пользователя
     *
     * @return bool
     */
    public function hasMessages()
    {
        return count($this->getMessages()) > 0;
    }

    /**
     * Возвращает сообщения
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Добавляем сообщение для пользователя к хелперу
     *
     * @param $message
     */
    protected function addMessage($message)
    {
        $this->messages[] = $message;
    }
}
