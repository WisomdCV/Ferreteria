<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class SupplierController extends Controller
{
    public function AllSupplier(){
        $supplier = Supplier::latest()->get();
        return view('backend.supplier.all_supplier',compact('supplier'));
    }//fin del metodo

    public function AddSupplier(){
        return view('backend.supplier.add_supplier');
    }//fin del método

    public function StoreSupplier(Request $request){

        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:customers|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'shopname' => 'required|max:200',
            'account_holder' => 'required|max:200',  
            'account_number' => 'required',  
            'type' => 'required',  
            'image' => 'required',  

        ],
        [
            'name.required' => 'Ingrese el nombre del proveedor aqui!'
            //customizar el mensaje
        ]
    
    );

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/supplier/'.$name_gen);
        $save_url = 'upload/supplier/'.$name_gen;

        Supplier::insert([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(), 

        ]);

        $notification = array(
            'message' => 'Proveedor Registrado con Exito!',  
            'alert-type' => 'success'
        );

        return redirect()->route('all.supplier')->with($notification); 
    } // End Method 

    public function EditSupplier($id){

        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.edit_supplier',compact('supplier'));

    }// End method

    public function UpdateSupplier(Request $request){

        $supplier_id = $request->id;
        //Esto especifica el id que se necesitará
        if ($request->file('image')) {

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/supplier/'.$name_gen);
            $save_url = 'upload/supplier/'.$name_gen;
    
            Supplier::findOrFail($supplier_id)->update([
    
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'shopname' => $request->shopname,
                'type' => $request->type,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'city' => $request->city,
                'image' => $save_url,
                'created_at' => Carbon::now(),   
    
            ]);
    
            $notification = array(
                'message' => 'Proveedor Actualizado con Exito!',  
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.supplier')->with($notification); 
            
        } else{

            Supplier::findOrFail($supplier_id)->update([
    
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'shopname' => $request->shopname,
                'type' => $request->type,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'city' => $request->city,
                'created_at' => Carbon::now(),   
    
            ]);
    
            $notification = array(
                'message' => 'Proveedor Actualizado con Exito!',  
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.supplier')->with($notification); 
            

        } // Final de la condicion ELSE
    }//Fin del m+étodo

    public function DeleteSupplier($id){

        $supplier_img = Supplier::findOrFail($id);
        $img = $supplier_img->image;
        unlink($img);

        Supplier::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Proveedor Eliminado con Exito!',  
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }//fin del metodo

    public function DetailsSupplier($id){

        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.details_supplier',compact('supplier'));

    }// End method
}