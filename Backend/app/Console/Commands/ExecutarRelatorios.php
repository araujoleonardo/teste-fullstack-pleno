<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExecutarRelatorios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:relatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa queries sql';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sqlFile = database_path('consultas.sql');

        if (!file_exists($sqlFile)) {
            $this->error("Arquivo SQL não encontrado: $sqlFile");
            return Command::FAILURE;
        }

        $sqlContent = file_get_contents($sqlFile);

        $queries = array_filter(array_map('trim', explode(';', $sqlContent)));

        foreach ($queries as $query) {
            $this->info("Executando query:");
            $this->line($query);

            try {
                $results = DB::select($query);

                if (!empty($results)) {
                    $this->table(array_keys((array)$results[0]), $results);
                } else {
                    $this->info("Query executada, sem resultados.");
                }

            } catch (\Exception $e) {
                $this->error("Erro ao executar query: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
