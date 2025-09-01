<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="antialiased">
        <div class="container m-auto">
            <h2 class="text-2xl">Usuários com mais produtos</h2>
            <br>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border border-collapse rounded sm:border-separate border-slate-200" cellspacing="0">
                    <tbody>
                    <tr>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">User ID</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Nome</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Total de Produtos</th>
                    </tr>
                    @foreach($userMaisProd as $item)
                        <tr>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->id }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->name }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->total_produto }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <br>

            <h2 class="text-2xl">Produto mais caro por usuário</h2>
            <br>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border border-collapse rounded sm:border-separate border-slate-200" cellspacing="0">
                    <tbody>
                    <tr>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">User ID</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">User Nome</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Produto ID</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Produto Nome</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Preço</th>
                    </tr>
                    @foreach($prodMaisCaro as $item)
                        <tr>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->user_id }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->user_name }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->prod_id }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->prod_name }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->price }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <br>

            <h2 class="text-2xl">Produto por faixa de preço</h2>
            <br>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border border-collapse rounded sm:border-separate border-slate-200" cellspacing="0">
                    <tbody>
                    <tr>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Faixa de preço</th>
                        <th scope="col" class="h-10 px-6 text-sm border border-gray-200">Total de produtos</th>
                    </tr>
                    @foreach($prodFaixaPreco as $item)
                        <tr>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->faixa_preco }}</td>
                            <td class="h-10 px-6 text-sm border border-gray-200">{{ $item->total_produto }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
