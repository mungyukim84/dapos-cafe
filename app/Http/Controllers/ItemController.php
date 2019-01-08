<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ItemService;

class ItemController extends Controller
{
  private $itemService;

  public function __construct(ItemService $itemService)
  {
      $this->middleware('auth');
      $this->itemService = $itemService;
  }

  public function insertItem(Request $request) {
    return response($this->itemService->insertItem($request->all()));
  }

  public function updateItem(Request $request, int $itemId) {
    return response($this->itemService->updateItem($request->all(), $itemId));
  }

  public function deleteItems(Request $request) {
    return response($this->itemService->deleteItems($request->itemIds));
  }

  public function getItemWithBarcode($barcode) {
    return response(['item' => $this->itemService->getItemWithBarcode($barcode)]);
  }

  public function getCategories(){
    return response(['categories' => $this->itemService->getCategories()]);
  }

  public function deleteCategory(Request $request) {
    return response($this->itemService->deleteCategory($request->categoryId));
  }

  public function updateCategories(Request $request) {
    return response($this->itemService->updateCategories($request->all()));
  }
}
