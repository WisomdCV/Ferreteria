<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Cierre de sesión de administrador realizado con éxito',
            'alert-type' => 'info'
        );

        return redirect('/logout')->with($notification);
    } //fin del metodo


    public function AdminLogoutPage(){

        return view('admin.admin_logout');
    } //fin del metodo

    public function AdminProfile(){
        
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view',compact('adminData'));
    } //fin del metodo
    
    public function AdminProfileStore(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_image/.$data->photo'));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_image'),$filename);
            $data['photo'] = $filename;
        }
        
        $data->save();

        $notification = array(
            'message' => 'Perfil de Administrador actualizado con éxito',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }//fin del metodo

    public function ChangePassword(){
        return view('admin.change_password');

    }//fin del metodo

    public function UpdatePassword(Request $request){

        //validacion
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',

        ]);

        ///Conincidencias con la contraseña antigua
        if (!Hash::check($request->old_password, auth::user()->password)) {

            $notification = array(
            'message' => 'La contraseña anterior no coincide!!',
            'alert-type' => 'error'
            ); 
            return back()->with($notification);

        }

        //// Actualización de la contraseña

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

            $notification = array(
            'message' => 'Cambio de contraseña realizado con éxito!',
            'alert-type' => 'success'
            ); 
            return back()->with($notification);

    }//fin del metodo
}
