<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShoppingListController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function togglePurchased(Request $request, $id)
    {
        
        if (!is_numeric($id)) {
            return response()->json(['success' => false, 'message' => 'Invalid ID format'], 400);
        }

        try {
            
            $item = ShoppingList::findOrFail($id);

            
            $this->authorize('update', $item);

            
            $item->update(['is_purchased' => !$item->is_purchased]);

            
            Log::info('Item toggled', [
                'item_id' => $item->id,
                'new_status' => $item->is_purchased,
                'user_id' => $request->user()->id ?? null,
            ]);

            
            return response()->json([
                'success' => true,
                'is_purchased' => $item->is_purchased,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error toggling item', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
}

{
    
{
    $query = ShoppingList::query();
    if ($request->filter == 'purchased') {
        $query->where('is_purchased', true);
    } elseif ($request->filter == 'not_purchased') {
        $query->where('is_purchased', false);
    }
    $items = $query->orderBy('created_at', 'desc')->paginate(10);
    return view('shopping_list.index', compact('items'));
}

}
