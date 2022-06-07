<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row">
                    <div class="col-md-6"><h3 class="font-weight-bold">Daftar Produk</h3></div>
                    <div class="col-md-6"> <input wire:model="search" type="text" class="form-control" placeholder="Search Products..."></div>                     
                </div>
            </div>
            <div class="card-body">                
                <div class="row">
                    @forelse($products as $product)
                        <div class="col-md-4 mb-3" :key="{{$product->id}}">
                            <div class="card" wire:click="addItem({{$product->id}})" style="cursor:pointer">
                                <div class="card-body">
                                    <img src="{{ asset('storage/images/'.$product->image)}}" alt="product" style="object-fit: contain  ;width:100%;height:170px">
                                    <h6 class="card-title text-center font-weight-bold mt-2">{{$product->name}}</h6>
                                    <h6 class="text-center font-weight-bold" style="color:grey">Rp {{number_format($product->price,2,',','.')}}</h6>
                                    @php $arr = explode(',', $product->description); @endphp
                                    @if(isset($arr[1]))
                                    <p class="card-text">{{$arr[0]}}</p>
                                    <ul class="pl-4">
                                        @foreach ($arr as $index => $item)
                                            @if($index != 0)
                                            <li>
                                                {{$item}}
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    @else
                                        <p class="card-text">{{$product->description}}</p>
                                    @endif
                                </div>
                                <div class="card-footer text-center p-2">
                                    <button class="btn btn-primary btn-sm">Keranjang <i class="fas fa-cart-plus fa-lg"></i></button>
                                </div>            
                            </div>
                        </div>
                    @empty
                    <div class="col-sm-12 mt-5">
                        <h2 class="text-center font-weight-bold text-primary">Produk Kosong</h2>                    
                    </div>                        
                    @endforelse                                     
                </div>
                <div style="display:flex;justify-content:center">
                    {{$products->links()}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white">
                <h3 class="font-weight-bold">Cart</h3>                              
            </div>
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
                <table class="table table-sm table-bordered table-hovered">
                    <thead class="bg-white">
                        <tr >
                            <th class="font-weight-bold">No</th>
                            <th class="font-weight-bold">Nama</th>
                            <th class="font-weight-bold" width="120px">Jumlah</th>
                            <th class="font-weight-bold">Harga</th>
                            <th class="font-weight-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($carts as $index=>$cart)
                            <tr>
                                <td>
                                    {{$index + 1}}
                                </td>
                                <td>
                                <a href="#" class="font-weight-bold text-dark">{{$cart['name']}}</a> 
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" style="padding:7px 10px"  wire:click="decreaseItem('{{$cart['rowId']}}')" {{ $cart['qty'] > 1 ? '' : 'disabled'}}><i class="fas fa-minus"></i></button>
                                    {{$cart['qty']}}
                                    <button class="btn btn-primary btn-sm" style="padding:7px 10px" wire:click="increaseItem('{{$cart['rowId']}}')"><i class="fas fa-plus"></i></button>
                                </td>
                                <td>Rp {{number_format($cart['price'],2,',','.')}}</td>
                                <td><button class="btn btn-danger p-1" wire:click="removeItem('{{$cart['rowId']}}')" style="font-size:13px;cursor:pointer;">DELETE</button></td>
                            </tr>                                
                        @empty
                            <td colspan="5"><h6 class="text-center">Empty Cart</h6></td>
                        @endforelse
                    </tbody>
                </table>              
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="font-weight-bold">Cart Summary</h4>
                <h5 class="font-weight-bold" id="oke">Total: Rp {{ number_format($summary['total'],2,',','.') }} </h5>

                {{-- <div class="row mt-4">
                    <div class="col-sm-6">
                         <button wire:click="enableTax" class="btn btn-primary btn-block btn-sm">Add Tax</button>
                    </div>
                    <div class="col-sm-6">
                          <button wire:click="disableTax" class="btn btn-info btn-block btn-sm">Remove Tax</button>
                    </div>      
                </div> --}}
                <input type="hidden" id="total" value="{{$summary['total']}}">
                <div class="mt-4">
                    <button data-toggle="modal" data-target="#modal-confirm" id="saveButton" class="btn btn-success btn-block font-weight-bold" id="saveButton">Pesan</button>
                </div>
                <form wire:submit.prevent="handleSubmit">
                    <button type="submit" class="d-none" id="confirmButton"></button>
                </form>
                <div wire:ignore.self class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pesanan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true close-btn">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Anda yakin memesan total nilai Rp. <span id="valueAlert">{{ number_format($summary['total'],2,',','.') }}</span>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Batalkan</button>
                                <button type="button" onclick="submit()" class="btn btn-success close-modal" data-dismiss="modal">Ya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('navbar').classList.add('d-none');
    function submit(){
        document.getElementById('confirmButton').click();
    }
</script>

