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
    public function checkout(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $product = Product::where('name', $request->product)->first();

        $transaction = new Transaction;
        $transaction->user_id = $user->id;
        $transaction->product_id = $product->id;
        $transaction->ordered_on = Carbon::now();
        $transaction->save();

        Notification::send($user, new InvoicePaidNotification());

        ExpiredStatus::dispatch($user->id)
            ->delay(now()->addMinutes(1));

        return response()->json([
            'message' => 'oke'
        ]);
    }
}