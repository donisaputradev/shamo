<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatter;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        try {
            $id = $request->input('id');
            $limit = $request->input('limit', 10);
            $status = $request->input('status');

            if ($id) {
                $transaction = Transaction::with(['items.product'])->find($id);

                if ($transaction) {
                    return ResponseFormatter::success($transaction, 'Transaction data retrieved successfully!');
                } else {
                    return ResponseFormatter::error(null, 'Transaction data does not exist!', 404);
                }
            }

            $transactions = Transaction::with(['items.product'])->where('user_id', Auth::user()->id);

            if ($status) {
                $transactions->where('status', $status);
            }

            return ResponseFormatter::success($transactions->paginate($limit), 'Transaction data retrieved successfully!');
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }

    public function submit(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'address' => 'required|String',
                'items' => 'required|array',
                'items*.id' => 'exists:products,id',
                'total_price' => 'required',
                'shipping_price' => 'required',
            ]);

            $validated = $validator->validated();

            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'address' => $validated['address'],
                'total_price' => $validated['total_price'],
                'numbtrans' => 'TRX' . date('YmdHis') . auth()->user()->id,
                'shipping_price' => $validated['shipping_price'],
            ]);

            foreach ($validated['items'] as $item) {
                TransactionDetail::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $item['id'],
                    'transaction_id' => $transaction->id,
                    'qty' => $item['qty'],
                ]);
            }

            return ResponseFormatter::success($transaction->load('items.product'), 'Transaction is successfully!');
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }
}
