<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'schedule.train', 'schedule.originStation', 'schedule.destinationStation'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'schedule.train', 'schedule.originStation', 'schedule.destinationStation']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|in:pending,paid,cancelled,completed']);
        $transaction->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status transaksi diperbarui.');
    }
}
