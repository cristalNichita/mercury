<?php

namespace Modules\Settings\Dict;

use Modules\Settings\Entities\GlobalDirectory;
use Modules\Settings\Entities\GlobalDirectoryItem;

class Dict
{

    public function get($code)
    {
        return GlobalDirectory::where('code', $code)->with('items')->first();
    }

    public function addItem($code, $item)
    {
        $dict = self::get($code);
        $dict->items()->save(new GlobalDirectoryItem($item));

        return $dict;
    }

    public function updateItem($itemId, $data)
    {
        $item = self::getItem($itemId);
        $item->update($data);

        return $item;
    }

    public function getItem($itemId)
    {
        return GlobalDirectoryItem::find($itemId);
    }

    public function deleteItem($itemId)
    {
        $item = self::getItem($itemId);
        $item->delete();
    }

    public function hasItem($code, $itemId)
    {
        $dict = self::get($code);
        return $dict->items->contains($itemId);
    }

}
