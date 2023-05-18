<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionExtraCharge;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Str;


class TransactionController extends Controller
{
    use RespondsWithHttpStatus;

    public function validate_data(Request $request, $id = null){
        $rules = [
            'room_id' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        return $validator;
    }    


    public function reservation(Request $request){
        $validator = $this->validate_data($request);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }
        
        $roomID = $request->room_id;
        $roomPrice = $request->room_price;
        
        $checkInDate = $request->check_in_date;
        $checkOutDate = $request->check_out_date;
    
        $checkInDateFormatted = convertDate($checkInDate);
        $checkOutDateFormatted = convertDate($checkOutDate);

        $numberOfDays = calculateDaysBetweenTwoDate($checkInDate, $checkOutDate);
        
        // Create the transaction
        $transaction = Transaction::create([
            'total_room_price' => $roomPrice,   // Initialize with the room price
            'total_extra_charge' => 0,          // Initialize with 0
            'final_total' => $roomPrice,        // Initialize with the room price
        ]);
    
        // Create the transaction detail
        $subPrice = $roomPrice * $numberOfDays;

        $transactionDetail = [
            'id' => Str::uuid(),
            'transaction_id' => $transaction->id,
            'room_id' => $roomID,
            'days' => $numberOfDays,
            'check_in_date' => $checkInDateFormatted,
            'check_out_date' => $checkOutDateFormatted,
            'price' => $roomPrice,
            'sub_price' => $subPrice,
        ];

        // Update the total room price and final total in the transaction
        $transaction->total_room_price = $subPrice;
        $transaction->final_total = $subPrice;
    
        // Insert the transaction details
        TransactionDetail::insert($transactionDetail);
    
        // Create the transaction extra charges
        $extraChargeIDs = $request->extra_charge_id;
        if ($extraChargeIDs !== null) {
            $prices = $request->price;
            $quantities = $request->quantity;
            $transactionExtraCharges = [];
            for ($i = 0; $i < count($extraChargeIDs); $i++) {
                $extraChargeID = $extraChargeIDs[$i];
                $price = $prices[$i];
                $quantity = $quantities[$i];
                $subPrice = $price * $quantity;
                $transactionExtraCharges[] = [
                    'id' => Str::uuid(),
                    'transaction_id' => $transaction->id,
                    'extra_charge_id' => $extraChargeID,
                    'quantity' => $quantity,
                    'price' => $price,
                    'sub_price' => $subPrice,
                ];
        
                // Update the total extra charge and final total in the transaction
                $transaction->total_extra_charge += $subPrice;
                $transaction->final_total += $subPrice;
            }
            // Insert the transaction extra charges
            TransactionExtraCharge::insert($transactionExtraCharges);
        }
    
        // Save the updated transaction
        $transaction->save();
    
        return $this->created($transaction, null);
    }

    function convertAndCalculateDays($dateString){
        $carbonDate = Carbon::createFromFormat('m/d/Y, h:i A', $dateString);
        $formattedDate = $carbonDate->format('Y-m-d H:i:s');
        $checkInDate = $carbonDate->format('Y-m-d');
    
        // Calculate the days based on check-in and check-out date
        $checkOutDate = $carbonDate->copy()->addDays($numberOfDays);
        $numberOfDays = $carbonDate->diffInDays($checkOutDate);
    
        return [
            'formatted_date' => $formattedDate,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'number_of_days' => $numberOfDays,
        ];
    }

    
}
