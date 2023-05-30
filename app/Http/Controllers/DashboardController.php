<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = User::where('roles', 'USER')->get();
        $transactionSuccess = Transaction::where('status', 'SUCCESS');
        $products = Product::all();
        return view('pages.home.index', [
            'title' => 'Dashboard',
            'transactions' => Transaction::orderBy('created_at', 'desc')->with('items.product')->paginate(20),
            'clients' => $clients,
            'totalPrice' => $transactionSuccess->sum('total_price'),
            'totalSuccess' => $transactionSuccess->get(),
            'products' => $products,
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('pages.home.edit', [
            'title' => 'Update Transaction',
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'status' => 'required',
        ]);

        Transaction::where('id', $transaction->id)->update($validatedData);

        return redirect()->route('index');
    }
}
