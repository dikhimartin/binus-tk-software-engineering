<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Transaction;
use App\TransactionDetail;
use App\Traits\RespondsWithHttpStatus;


class TransactionController extends Controller
{
    use RespondsWithHttpStatus;

    public function order(Request $request){
        $productIds = $request->input('product_id');
        $productPrices = $request->input('product_price');
        $quantities = $request->input('quantity');
    
        $data = array();
        $customer = new Customer;
        $customerID = $customer->get_customer_id();
        if ($customerID) {
            $data['customer_id'] = $customerID;
        }
        
        $transaction = Transaction::create($data);
        for ($i = 0; $i < count($productIds); $i++) {
            $sub_price = $productPrices[$i] * $quantities[$i];
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $productIds[$i],
                'quantity' => $quantities[$i],
                'price' => $productPrices[$i],
                'sub_price' => $sub_price,
            ]);
        }
        
        return $this->created($transaction, null);
    }
}
