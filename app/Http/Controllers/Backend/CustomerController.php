<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    public function AllCustomer(){
        $customer = Customer::latest()->get();
        return view('backend.customer.all_customer',compact('customer'));
        
    }//fin del metodo

    public function AddCustomer(){

        return view('backend.customer.add_customer');
    
    }//fin del metodo

    public function StoreCustomer(Request $request){

        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:customers|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'shopname' => 'required|max:200',
            'account_holder' => 'required|max:200',  
            'account_number' => 'required',  
            'image' => 'required',  

        ],
        [
            'name.required' => 'Ingrese el nombre del cliente aqui!'
            //customizar el mensaje
        ]
    
    );

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/customer/'.$name_gen);
        $save_url = 'upload/customer/'.$name_gen;

        Customer::insert([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'shopname' => $request->shopname,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(), 

        ]);

        $notification = array(
            'message' => 'Cliente Registrado con Exito!',  
            'alert-type' => 'success'
        );

        return redirect()->route('all.customer')->with($notification); 
    } // End Method 

    public function EditCustomer($id){

        $customer = Customer::findOrFail($id);
        return view('backend.customer.edit_customer',compact('customer'));

    }// End method

    public function UpdateCustomer(Request $request){

        $customer_id = $request->id;
        //Esto especifica el id que se necesitará
        if ($request->file('image')) {

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/customer/'.$name_gen);
            $save_url = 'upload/customer/'.$name_gen;
    
            Customer::findOrFail($customer_id)->update([
    
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'shopname' => $request->shopname,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'city' => $request->city,
                'image' => $save_url,
                'created_at' => Carbon::now(),     
    
            ]);
    
            $notification = array(
                'message' => 'Cliente Actualizado con Exito!',  
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.customer')->with($notification); 
            
        } else{

            Customer::findOrFail($customer_id)->update([
    
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'shopname' => $request->shopname,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'city' => $request->city,
                'created_at' => Carbon::now(),     
    
            ]);
    
            $notification = array(
                'message' => 'Cliente Actualizado con Exito!',  
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.customer')->with($notification);
            

        } // Final de la condicion ELSE
    }//Fin del metodo

    public function DeleteCustomer($id){

        $customer_img = Customer::findOrFail($id);
        $img = $customer_img->image;
        unlink($img);

        Customer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Cliente Eliminado con Exito!',  
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }//fin del metodo

}
