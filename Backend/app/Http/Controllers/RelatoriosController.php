<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RelatoriosController extends Controller
{
    public function relatorios()
    {
        $userMaisProd = collect();
        $prodMaisCaro = collect();
        $prodFaixaPreco = collect();

        if (Schema::hasView('usuario_mais_produto')) {
            $userMaisProd = DB::table('usuario_mais_produto')->get();
        }

        if (Schema::hasView('produto_mais_caro')) {
            $prodMaisCaro = DB::table('produto_mais_caro')->get();
        }

        if (Schema::hasView('produto_faixa_preco')) {
            $prodFaixaPreco = DB::table('produto_faixa_preco')->get();
        }

        return view('relatorios', compact('userMaisProd', 'prodMaisCaro', 'prodFaixaPreco'));
    }
}
