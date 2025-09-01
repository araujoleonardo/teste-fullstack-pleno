# Aplicação Laravel Para teste Full-Stack

## Tecnologias Usadas

- Backend
    - Laravel 10
    - Php 8.2
    - Swagger
    - Jwt
- Frontend
    - Vuejs 3
    - Composition API
    - Pinia
    - Axios
    - vue-router
    - vuetify
- Banco
    - Postgres 16
- Docker
    - docker compose

<hr> 

## Instruções de Uso

1. Clone o resitorio do github.</br></br>
```
git clone https://github.com/araujoleonardo/teste-fullstack-pleno.git
```

2. Acesse a pasta do repositorio clonado.</br></br>
```
cd teste-fullstack-pleno/Backend
```

3. Cria uma copia do arquivo .env.example.</br></br>
```
cp .env.example .env
```

4. Abra o arquivo .env criado.</br></br>
```
nano .env
```

5. Altere as linhas que achar necessárias.</br></br>
```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=postgres
DB_PASSWORD=1234

PGADMIN_DEFAULT_EMAIL='pgadmin4@teste.com'
PGADMIN_DEFAULT_PASSWORD=1234
```

6. Salve as alterações.</br></br>

7. Crie os container no docker.</br></br>
```
docker-compose up -d
```

8. Após criar as imagens abra o container da aplicação.</br></br>
```
docker exec -it laravel-app bash
```

9. Instale todas as dependências do laravel.</br></br>
```
composer install
```

10. Defina a APP_KEY do arquivo .env .</br></br>
```
php artisan key:generate
```

11. Faça a migração de todas as tabelas do banco.</br></br>
```
php artisan migrate
```

12. Execute os Seed para popular o banco e criar o usuário de acesso.</br></br>
```
php artisan db:seed
```

### Abra a plicação no navegador em http://localhost. </br>

## Obs:
### A aplicação frontend já está buildada e encapsulada nas rotas do laravel, ao abrir o http://localhost as rotas ja irão reconecer a aplicação do vue, mais caso queira executar em modo desenvolvimento, abaixo está o passo a passo

`Primeiro saia do container da aplicação ou abra um novo terminal`

13. Abrir a pasta do Frontend.</br></br>

```
cd Frontend/
```

14. Instalar dependencia.</br></br>

```
npm i
```

15. Copiar arquivo .env.</br></br>

```
cp .env.example .env
```

16. Executar aplicação.</br></br>

```
npm run dev
```

#### A aplicação poderá ser aberta na porta 3000 o http://localhost:3000

## Para ter acesso aos relatorios SQL
### Rode o comando personalizado dentro do container da aplicação

``
php artisan app:relatorios
``

Este comando irá gerar **views** no banco usando o arquivo de consulta disponivel no caminho
``
Backend/database/consultas.sql
``

Após rodar o comando e correr com sucesso, confirme se as views foram criadas.
### Abra na rota http://localhost/relatorio-sql

Obs: As views foram cridas para facilitar a visualização dos dados
