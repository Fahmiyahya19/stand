<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product as ProductModel;
use Illuminate\Support\Facades\Storage;

class Product extends Component
{
    use WithFileUploads;

    public $idProduct,$name,$image,$description,$stock,$price,$updateProduct = false;
    
    protected $rules = [
        'name' => 'required',
        'image' => '',
        'description' => 'string',
        'stock' => 'required',
        'price' => 'required',            
    ];

    public function render()
    {
        $products = ProductModel::active()->orderBy('created_at', 'DESC')->get();
        return view('livewire.product', [
            'products' => $products
        ]);
    }

    public function previewImage(){
        $this->validate([
            'image' => 'image|max:2048'
        ]);
    }

    public function store(){
        $this->rules['image'] = 'image|max:4096|required';
        $this->validate();

        $imageName = md5($this->image.microtime()).'.'.$this->image->extension();

        Storage::putFileAs(
            'public/images',
            $this->image,
            $imageName
        );

        ProductModel::create([
            'name' => $this->name,
            'image' => $imageName,
            'description' => $this->description,
            'qty' => $this->stock,
            'price' => $this->price 
        ]);

        session()->flash('info', 'Product Created Successfully');

        $this->deleteInput();
    }

    public function getProduct($product)
    {
        $this->updateProduct = true;
        $model = ProductModel::findOrFail($product);
        $this->idProduct = $model->id;
        $this->name = $model->name;
        $this->image = $model->image;
        $this->description = $model->description;
        $this->stock = $model->qty;
        $this->price = $model->price;
    }

    public function update(){
        if(!Storage::exists('public/images/'.$this->image)){
            $this->rules['image'] = 'image|max:4096';
        }
        $this->validate();
        $model = ProductModel::findOrFail($this->idProduct);
        if($model->image != $this->image){ 
            if(Storage::exists('public/images/'.$this->image)){Storage::delete('public/images/'.$this->image);}
            $imageName = md5($this->image.microtime()).'.'.$this->image->extension();
            Storage::putFileAs(
                'public/images',
                $this->image,
                $imageName
            );
        }

        $model->update([
            'name' => $this->name,
            'image' => $imageName ?? $model->image,
            'description' => $this->description,
            'qty' => $this->stock,
            'price' => $this->price 
        ]);
        session()->flash('info', 'Product Updated Successfully');
        $this->updateProduct = false;
        $this->deleteInput();
    }

    public function cencelEdit(){
        $this->updateProduct = false;
        $this->deleteInput();
    }

    public function destroy($idProduct){
        ProductModel::findOrFail(($idProduct))->update(['deleted' => 1]);
        session()->flash('info', 'Product Deleted Successfully');
    }

    private function deleteInput(){
        $this->name = '';
        $this->image = '';
        $this->description = '';
        $this->stock = '';
        $this->price = '';
    }
}
