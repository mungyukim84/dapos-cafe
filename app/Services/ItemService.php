<?php
namespace App\Services;

use Auth;
use DB;
use App\Cafe_Category;
use App\Cafe_Menu;

class ItemService {

  public function getMenu() {
    return Cafe_Category::with('cafeMenu')->where('is_delete', 'N')->orderBy('tab_order')->get();
  }

  public function getCategories() {
    return Cafe_Category::where('is_delete', 'N')->orderBy('tab_order')->get();
  }

  public function insertItem(Array $data) {
//    dd($data);
    $data['created_by'] = Auth::user()->id;
    try {
      Cafe_Menu::create($data);
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => 'Fail to insert Item', 'err' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  public function updateItem(Array $data, int $itemId) {
    $data['updated_at'] = date('Y-m-d H:i:s');
    $data['updated_by'] = Auth::user()->id;
    try {
      Cafe_Menu::where('id', $itemId)->update($data);
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => 'Fail to update Item', 'err' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  public function deleteItems($itemIds) {
    try {
      Cafe_Menu::whereIn('id', $itemIds)->update([
        'is_delete' => 'Y',
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => Auth::user()->id
      ]);
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => 'Fail to delete Items', 'err' => $e->getMessage()];
    }

    return ['ok' => true];
  }

  public function getItemWithBarcode($barcode) {
    return Cafe_Menu::where('ean', $barcode)
                    ->orWhere('ean_2', $barcode)
                    ->orWhere('ean_3', $barcode)
                    ->first();
  }

  public function deleteCategory($categoryId) {
    try {
      Cafe_Category::where('id', $categoryId)->update([
        'is_delete' => 'Y',
        'tab_order' => null,
        'updated_by' => Auth::User()->id,
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => 'Fail to delete Category', 'err' => $e->getMessage()];
    }

    return ['ok' => true];
  }

  public function updateCategories($data) {
    try {
      Cafe_Category::where('is_delete', 'N')->update(['tab_order' => NULL]);
      foreach($data['categories'] as $category) {
        if($category['id'] == 'new') {
          unset($category['id']);
          $category['created_by'] = Auth::User()->id;
          Cafe_Category::create($category);
        }
        else {
          Cafe_Category::where('id', $category['id'])->update($category);
        }
      }
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => 'Fail to update Category', 'err' => $e->getMessage()];
    }
    return ['ok' => true];
  }
}
