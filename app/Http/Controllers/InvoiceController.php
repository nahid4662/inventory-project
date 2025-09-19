<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoiceController extends Controller
{


   function SalePage(Request $request)
    {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        $products = Product::where('user_id', $user_id)->get();
        return Inertia::render('SalePage', [
            'customers' => $customers,
            'products' => $products
        ]);
    }

    function InvoiceListPage(Request $request)
    {
        $user_id=$request->header('id');
        $list=Invoice::where('user_id',$user_id)->with('customer')->get();
        return Inertia::render('InvoiceListPage',['list'=>$list]);
    }


    function InvoiceDetailsPage(Request $request)
    {
        $user_id=$request->header('id');
        $customerDetails=Customer::where('user_id',$user_id)->first();
        $invoiceTotal=Invoice::where('user_id',$user_id)->first();
        $invoiceProduct=InvoiceProduct::where('invoice_id',$request->query('id'))
            ->where('user_id',$user_id)->with('product')
            ->get();
        return Inertia::render('InvoiceDetailsPage',[
            'customer'=>$customerDetails,
            'invoice'=>$invoiceTotal,
            'product'=>$invoiceProduct,
        ]);
    }

    public function invoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');
            $customer_id = $request->input('customer_id');
            $products = $request->input('products');

            // Validate main invoice fields
            if (!$customer_id || !is_array($products) || count($products) == 0) {
                DB::rollBack();
                $data = ['message' => 'Invalid data. Please select customer and at least one product.', 'status' => false];
                return redirect()->route('InvoiceListPage')->with($data);
            }

            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
            ]);

            $invoiceID = $invoice->id;
            foreach ($products as $EachProduct) {
                // SaleForm uses keys: product_id, unit, price
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['unit'],
                    'sale_price' => $EachProduct['price'],
                ]);
            }

            DB::commit();
            $data = ['message' => 'Invoice Created Successfully', 'status' => true];
            return redirect()->route('InvoiceListPage')->with($data);
        } catch (Exception $e) {
            DB::rollBack();
            $data = ['message' => 'Invoice creation failed: ' . $e->getMessage(), 'status' => false];
            return redirect()->route('InvoiceListPage')->with($data);
        }
    }


    function invoiceSelect(Request $request){
        $user_id=$request->header('id');
        return Invoice::where('user_id',$user_id)->with('customer')->get();
    }

    function InvoiceDetails(Request $request){
        $user_id=$request->header('id');
        $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();
        $invoiceTotal=Invoice::where('user_id','=',$user_id)->where('id',$request->input('inv_id'))->first();
        $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))
            ->where('user_id',$user_id)->with('product')
            ->get();
        return redirect()->route('InvoiceListPage')->with([
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,
        ]);

    }


    function invoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $invoice_id = $request->route('id');

            InvoiceProduct::where('invoice_id', $invoice_id)
                ->where('user_id', $user_id)
                ->delete();

            Invoice::where('id', $invoice_id)->delete();

            DB::commit();
            return redirect()->route('InvoiceListPage')->with(['message'=>'Delete Invoice Successful','status'=>true]);
        }
        catch (Exception $e){
            DB::rollBack();
            return redirect()->route('InvoiceListPage')->with(['message'=>'Delete Invoice Fail','status'=>false,'error'=>$e->getMessage()]);
        }
    }
}
