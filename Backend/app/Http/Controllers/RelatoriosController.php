<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    public function relatorios()
    {
        $userMaisProd = DB::table('usuario_mais_produto')->get();
        $prodMaisCaro = DB::table('produto_mais_caro')->get();
        $prodFaixaPreco = DB::table('produto_faixa_preco')->get();

        return view('relatorios', compact('userMaisProd', 'prodMaisCaro', 'prodFaixaPreco'));
    }
}
