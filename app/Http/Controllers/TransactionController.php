<?php

namespace App\Http\Controllers;

use App\Jobs\ExpiredStatus;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\InvoicePaidNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    public function show()
    {
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $product = Product::where('name', $request->product)->first();
        $id = date('YmdHis');

        $transaction = new Transaction;
        $transaction->id = $id;
        $transaction->user_id = $user->id;
        $transaction->product_id = $product->id;
        $transaction->ordered_on = now();
        $transaction->save();

        Notification::send($user, new InvoicePaidNotification());

        ExpiredStatus::dispatch($id)
            ->delay(now()->addMinutes(3));

        return response()->json([
            'message' => 'oke'
        ], 200);
    }
}
