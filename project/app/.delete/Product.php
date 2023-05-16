<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use DB;

class Product extends Model
{
    Use Uuid;

    protected $table = 'products';
    protected $fillable = [
        'product_type_id',
        'asset_id',
        'name',
        'description',
        'stock',
        'purchase_price',
        'selling_price',
        'status'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';
    
    // Query Builder version
    public function get_data(){
        $data = Product::select(
            'products.id',
            'products.product_type_id',
            'products.asset_id',
            'product_types.name as product_type_name',
            'products.name',
            'products.description',
            'products.stock',
            'products.purchase_price',
            'products.selling_price',
            'products.status',
            'assets.absolute_path as assets_absolute_path',
            'assets.relative_path as assets_relative_path',
            'assets.file_name as assets_name',
            'products.created_at',
            'products.updated_at') 
        ->leftjoin('assets', 'products.asset_id', '=', 'assets.id')
        ->leftjoin('product_types', 'products.product_type_id', '=', 'product_types.id')
        ->orderBy('product_type_name', 'desc'); // add this line to order by product_type_name

        return $data;
    }

    public function best_selling_products($start_date, $end_date){
        $query = TransactionDetail::select('products.name', DB::raw('SUM(quantity) as total_quantity'))
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status_transaction', '=', 'finish')
            ->groupBy('products.name')
            ->orderBy('total_quantity', 'desc')
            ->take(10);
    
        if ($start_date !== null) {
            $query->whereDate('transactions.transaction_date', '>=', $start_date);
        }
    
        if ($end_date !== null) {
            $query->whereDate('transactions.transaction_date', '<=', $end_date);
        }
    
        $results = $query->get();
    
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'name' => $result->name,
                'data' => [(int)$result->total_quantity]
            ];
        }
        return $formattedResults;
    }
}
