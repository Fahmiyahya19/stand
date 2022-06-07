<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductTransaction;
use Livewire\Component;
use App\Models\Transaction;
use Barryvdh\DomPDF\PDF;

class Penjualan extends Component
{

    public $invoice_number,$total,$products,$bayar,$paid,$cencel,$search,$note, $pelanggan, $view = false;

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
            'bayar' => 'required|gte:'.$this->total,
            'pelanggan' => 'required'
        ],[
            'gte' => 'Pembayaran Harus Sama atau Lebih Dari :value'
        ]);

        $model = Transaction::findOrFail($this->invoice_number);
        $model->update([
            'pelanggan' => $this->pelanggan,
            'paid' => 1,
            'note' => $this->note,
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
        $this->note = $model->note;
        $this->bayar = $model->pay;
        $this->total = $model->total;
        $this->pelanggan = $model->pelanggan;
        if($this->view){
            $this->view = false;
        }
    }

    // public function nota($invoice_number){
    //     redirect()->route('cetak', ['invoice' => $invoice_number]);
    // }

    public function view($invoice_number){
        $this->getInv($invoice_number);
        $this->view = true;
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
        $this->note = '';
        $this->bayar = '';
        $this->total = '';
        $this->pelanggan = '';
    }
}
