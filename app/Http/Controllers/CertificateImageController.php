<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificates;
use DB;
use App\Helper\File;
class CertificateImageController extends Controller
{
    use File;
    //
    public function index()
    {
        try {
            $certificate = Certificates::get();
        return view('certificate.index',['certificate'=>$certificate]);
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }

    public function create() 
    {
        try {
        return view('certificate.create');
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function store(Request $request)
    {
      
        try {
     
            if( $file = $request->file('image') ) {
                $path = 'uploads/certificates';
                $image = $this->file($file,$path,387,673);
            }else{$image='defalut.jpg';}

        DB::transaction(function () use ($request,$image) {
            $certificate=new Certificates;
            $certificate->image=$image;
            $certificate->save();
        }); 
        return back();   
    } catch (\Exception $e) {
        return $e->getMessage();
      }     
    
    }
   
    public function destroy(Certificates $certificate) 
    {
       
        try {
            DB::transaction(function () use ($certificate) {
            $certificate->delete();
        }); 
            return redirect()->route('certificate.index')->with('success','certificate Image deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
          }
      
    }

    
}
