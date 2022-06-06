<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold mb-3">Product List</h2>
                    <table class="table table-bordered table-hovered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th width="20%">Image</th>
                                <th>Description</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index=>$product)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$product->name}}</td>
                                <td><img src="{{ asset('storage/images/'.$product->image)}}" alt="product image" class="img-fluid"></td>
                                <td>{{$product->description}}</td>
                                <td>{{$product->qty}}</td>
                                <td>{{$product->price}}</td>
                                <td>
                                    <div>
                                        <button wire:click="getProduct({{$product->id}})" class="btn btn-warning btn-sm">Edit</button>
                                        <button wire:click="destroy({{$product->id}})" class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                </td>                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{session('error')}}
                        </div>
                    @elseif(session()->has('info'))
                        <div class="alert alert-success">
                            {{session('info')}}
                        </div>
                    @endif    
                    @if ($updateProduct)
                    <h2 class="font-weight-bold mb-3">Update Product</h2>
                    <form wire:submit.prevent="update">
                        <input wire:model="idProduct" type="hidden">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input wire:model="name" type="text" class="form-control">
                            @error('name') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <div class="custom-file">
                                <input wire:model="image" type="file" class="custom-file-input" id="customFile">
                                <label for="customFile" class='custom-file-label'>Choose Image</label>
                                @error('image') <small class="text-danger">{{$message}}</small>@enderror
                            </div>
                            @if($image)
                                <label class="mt-2">Image Preview:</label>
                                <img src="{{ Storage::exists('public/images/'.$image) ? asset('storage/images/'.$image) : $image->temporaryUrl() }}" class="img-fluid" alt="Preview Image">
                            @endif                            
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea wire:model="description" class="form-control"></textarea>
                            @error('description') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input wire:model="stock" type="number" class="form-control">
                            @error('stock') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input wire:model="price" type="number" class="form-control">
                            @error('price') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <button wire:click="cencelEdit()" type="button" class="btn btn-secondary btn-block">Batalkan</button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <h2 class="font-weight-bold mb-3">Create Product</h2>
                    <form wire:submit.prevent="store">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input wire:model="name" type="text" class="form-control">
                            @error('name') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <div class="custom-file">
                                <input wire:model="image" type="file" class="custom-file-input" id="customFile">
                                <label for="customFile" class='custom-file-label'>Choose Image</label>
                                @error('image') <small class="text-danger">{{$message}}</small>@enderror
                            </div>
                            @if($image)
                                <label class="mt-2">Image Preview:</label>
                                <img src="{{$image->temporaryUrl()}}" class="img-fluid" alt="Preview Image">
                            @endif                            
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea wire:model="description" class="form-control"></textarea>
                            @error('description') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input wire:model="stock" type="number" class="form-control">
                            @error('stock') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input wire:model="price" type="number" class="form-control">
                            @error('price') <small class="text-danger">{{$message}}</small>@enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h3>{{ $name }}</h3>
                    <h3>{{ $image }}</h3>
                    <h3>{{ $description }}</h3>
                    <h3>{{ $stock }}</h3>
                    <h3>{{ $price }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script-custom')
<script>
    document.addEventListener('livewire:load', function () {
        @this.on('destroy', idProduct => {
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
            }).then((result) => {
                //if user clicks on delete
                if (result.value) {
                    // calling destroy method to delete
                    @this.call('destroy',idProduct)

                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'File Tidak Jadi Dihapus :)',
                        'error'
                    )
                }
            });
        })
    })
</script>
@endpush