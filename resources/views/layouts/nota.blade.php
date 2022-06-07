<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        body{
            font-size: 14px;
            width: 8cm;
            font-family: Arial, sans-serif;
        }

        .table-produk{
            font-size: 14px;
        }

        .table-produk tbody::after {
	        content: '';
	        display: block;
	        height: 20px;
        }

        @media print {
	        body{
                font-size: 14px;
		        width: 8cm;
                font-family: Arial, sans-serif;
	        }

	        .table-produk{
                font-size: 14px;
	        }

	        .table-produk tbody::after {
		        content: '';
		        display: block;
		        height: 20px;
	        }
        }
    </style>
</head>
<body>
    <h4 class="text-center" style="width: 8cm;">RPL 2022</h4>
    <table width="100%" style="width: 8cm;">
        <tbody>
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($model->created_at)) }}</td>
            </tr>
            <tr>
                <td>{{ $model->invoice_number }}</td>
                <td class="text-right">{{ $model->pelanggan }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="table-produk">
        <thead>
            <tr style="border-top: 1px solid; border-bottom: 1px solid;">
                <th class="font-weight-bold">Nama</th>
                <th class="font-weight-bold">Harga</th>
                <th class="font-weight-bold" width="10px">Jumlah</th>
                <th class="font-weight-bold text-right">Total</th>
            </tr>
        </thead>
        <tbody>
	        @if(!empty($model->products))
                @foreach($model->products as $index => $product)
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
        <tfoot>
            <tr style="border-top: 1px solid;">
                <td colspan="3" class="text-right" style="margin-top: 10cm;">Total</td>
                <td class="text-right">Rp. {{ number_format($model->total, 2, ',',  '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Bayar</td>
                <td class="text-right">Rp. {{ number_format($model->pay, 2, ',',  '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Kembali</td>
                @php 
                    $kembali = (float)$model->pay - (float)$model->total;
                @endphp
                <td class="text-right">Rp. {{ number_format($kembali, 2, ',',  '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <br>
    <p class="text-left">Note: {{ nl2br($model->note) }}</p>
    <script type="text/javascript">
	    window.onload = function() {
		    window.print();
	    }
    </script>
</body>
</html>