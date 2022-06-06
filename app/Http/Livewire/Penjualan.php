<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductTransaction;
use Livewire\Component;
use App\Models\Transaction;

class Penjualan extends Component
{

    public $invoice_number,$total,$products,$bayar,$paid,$cencel,$search;

    public function search(){
        $this->resetPage();
    }
    
    public function render()
    {
        $penjualans = Transaction::where('invoice_number', 'like', '%'.$this->search.'%')
                    ->orderBy('paid', 'asc')
                    ->orderBy('created_at', 'desc')->get();
        return view('livewire.penjualan', [
            'penjualans' => $penjualans
        ]);
    }

    public function update(){
        $this->validate([
            'bayar' => 'required|gte:'.$this->total
        ],[
            'gte' => 'Pembayaran Harus Sama atau Lebih Dari :value'
        ]);

        $model = Transaction::findOrFail($this->invoice_number);
        $model->update([
            'paid' => 1,
            'pay' => $this->bayar
        ]);

        session()->flash('info', 'Pembayaran '.$this->invoice_number.' Berhasil');

        $this->deleteInput();
    }

    public function getInv($invoice_number)
    {
        $model = Transaction::with(['products.product'])->findOrFail($invoice_number);
        $this->invoice_number =  $model->invoice_number;
        $this->products = $model->products;
        $this->bayar = $model->pay;
        $this->total = $model->total;
    }

    public function cencelEdit(){
        $this->deleteInput();
    }

    public function destroy($idPenjualan){
        $penjualan = Transaction::findOrFail(($idPenjualan));
        $products = ProductTransaction::where('invoice_number', $idPenjualan)->get();
        if($products){
            foreach($products as $key => $value){
                $product = Product::find($value->product_id);
                if($product){
                    $product->update(['qty' => $product->qty + $value->qty]);
                }
            }
        }
        ProductTransaction::where('invoice_number', $idPenjualan)->delete();
        if($penjualan->paid){
            session()->flash('error', 'Penjualan Sudah Selesai, Tidak Dapat Dihapus');
        }else{
            $penjualan->delete();
            session()->flash('info', 'Penjualan Deleted Successfully');
        }
    }

    private function deleteInput(){
        $this->invoice_number =  '';
        $this->products = '';
        $this->bayar = '';
        $this->total = '';
    }
}