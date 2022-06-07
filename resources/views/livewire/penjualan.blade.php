<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row">
                    <div class="col-md-6"><h3 class="font-weight-bold">Daftar Transaksi</h3></div>
                    <div class="col-md-6"> <input wire:model="search" type="text" class="form-control" placeholder="Search Invoice Number..."></div>                     
                </div>
            </div>
            <div class="card-body">                
                <table class="table table-bordered table-hovered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice Number</th>
                            <th width="30%">Total</th>
                            <th>Bayar</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualans as $index=>$penjualan)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$penjualan->invoice_number}}</td>
                            <td class="text-right">Rp. {{number_format($penjualan->total, 2, ',', '.')}}</td>
                            <td class="text-right">Rp. {{number_format($penjualan->pay, 2, ',', '.')}}</td>
                            <td>
                                @if($penjualan->paid)
                                    <span class="badge badge-success">Paid</span>
                                @elseif($penjualan->cencel)
                                    <span class="badge badge-danger">Cencel</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif
                            </td>
                            <td>{{ $penjualan->note }}</td>
                            <td>
                                <div>
                                    @if(!$penjualan->paid)
                                    <button wire:click="getInv('{{$penjualan->invoice_number}}')" class="btn btn-primary btn-sm p-1">Payment</button>
                                    <button wire:click="destroy('{{$penjualan->invoice_number}}')" class="btn btn-danger btn-sm p-1">Delete</button>
                                    @else
                                    <button wire:click="view('{{$penjualan->invoice_number}}')" class="btn btn-info btn-sm p-1">View</button>
                                    <a href="{{ route('cetak', ['invoice' => $penjualan->invoice_number]) }}" target="_blank" class="btn btn-primary btn-sm p-1">Nota</a>
                                    {{-- <button onclick="nota('{{$penjualan->invoice_number}}')" class="btn btn-primary btn-sm p-1">Nota</button> --}}
                                    @endif
                                </div>
                            </td>                                
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white">
                <h3 class="font-weight-bold">{{ $view ? 'View' : 'Payment'}}</h3>                              
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
                <form wire:submit.prevent="update">
                    <div class="form-group">
                        <label>Invoice Number</label>
                        <input wire:model="invoice_number" type="text" class="form-control" readonly>
                        @error('invoice_number') <small class="text-danger">{{$message}}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label>Pelanggan</label>
                        <input wire:model="pelanggan" type="text" class="form-control">
                        @error('pelanggan') <small class="text-danger">{{$message}}</small>@enderror
                    </div>
                    <div>
                        <table class="table table-sm table-bordered table-hovered">
                            <thead class="bg-white">
                                <tr>
                                    <th class="font-weight-bold">Nama</th>
                                    <th class="font-weight-bold">Harga</th>
                                    <th class="font-weight-bold" width="10px">Jumlah</th>
                                    <th class="font-weight-bold">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($products))
                                    @foreach($products as $index=>$product)
                                        <tr>
                                            <td >
                                            <a href="#" class="font-weight-bold text-dark">{{$product->product->name}}</a> 
                                            <td class="text-right">
                                                Rp {{number_format($product->product->price,2,',','.')}}
                                            </td>
                                            </td>
                                            <td class="text-center">{{$product->qty}}</td>
                                            <td class="text-right">
                                                @php
                                                    $totalProducts = (float)$product->product->price * (float)$product->qty;
                                                @endphp
                                                Rp {{number_format($totalProducts,2,',','.')}}
                                            </td>
                                        </tr>                                
                                    @endforeach
                                @else
                                <td colspan="4"><h6 class="text-center">Empty Product</h6></td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea wire:model="note" id="note" class="form-control" {{ $view ? 'disabled' : ''}}></textarea>
                        @error('note') <small class="text-danger">{{$message}}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label>Total</label>
                        <input wire:model="total" id="total" type="text" class="form-control" readonly>
                        @error('total') <small class="text-danger">{{$message}}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label>Bayar</label>
                        <input wire:model="bayar" id="bayar" type="number" class="form-control" {{ $view ? 'disabled' : ''}}>
                        @error('bayar') <small class="text-danger">{{$message}}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label >Kembalian</label>
                        <h1 id="kembalianText" wire:ignore>Rp. 0</h1>
                    </div>
                    <div class="form-group {{ $view ? 'd-none' : '' }}">
                        <div class="row">
                            <div class="col-6">
                                <button wire:click="cencelEdit()" type="button" class="btn btn-secondary btn-block">Batalkan</button>
                            </div>
                            <div class="col-6">
                                <button wire:ignore type="submit" id="bayarButton" disabled class="btn btn-primary btn-block">Bayar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function nota(invoice_number){
        var win = window.open('{{ route('cetak') }}/'+invoice_number, 'nota', 'width=20,height=20');
        setTimeout(function() {
            win.close();
        }, 5000);
    }
</script>

@push('script-custom')
    <script>
        bayar.oninput = () => {
            const bayarAmount = document.getElementById("bayar").value
            const totalAmount = document.getElementById("total").value
            console.log(bayarAmount);
            console.log(totalAmount);
            let kembalian = 0
            if(parseFloat(bayarAmount) >= parseFloat(totalAmount)){
                kembalian =  parseFloat(bayarAmount) - parseFloat(totalAmount)
            }

            document.getElementById("kembalianText").innerHTML = `Rp ${rupiah(kembalian)},00`

            const bayarButton =  document.getElementById("bayarButton")

            if(kembalian < 0){
                bayarButton.disabled = true
            }else{
                bayarButton.disabled = false
            }
        }
    </script>
@endpush


