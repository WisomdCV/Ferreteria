<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Validación
        if (!isset($row[0]) || empty($row[0])) {
            //Esto maneja el error o simplemente salta de fila
            return null;
        }

        return new Product([
            'product_name' => $row[0],
            'category_id' => $row[1],
            'supplier_id' => $row[2],
            'product_code' => $row[3],
            'product_garage' => $row[4],
            'product_image' => $row[5],
            'product_store' => $row[6],
            'buying_date' => Carbon::parse($row[7]), //Asegura Fechas
            'expire_date' => Carbon::parse($row[8]),
            'buying_price' => $row[9],
            'selling_price' => $row[10], 
        ]);
    }
}
