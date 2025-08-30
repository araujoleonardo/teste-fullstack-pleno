<?php

namespace App\Http\Controllers;

use App\DTO\FormDTO\UserFormDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\User\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected IUserService $service;

    public function __construct(IUserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/user/get-users",
     *     summary="Lista todos os usuários",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function getUsers(): JsonResponse
    {
        $users = $this->service->getUsers();

        return response()->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Listar usuários com filtro e paginação",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Texto de busca (por nome, email, etc.)",
     *         required=false,
     *         @OA\Schema(type="string", example="João")
     *     ),
     *     @OA\Parameter(
     *         name="field",
     *         in="query",
     *         description="Campo para ordenação (ex: name, created_at)",
     *         required=false,
     *         @OA\Schema(type="string", example="name")
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Direção da ordenação (asc ou desc)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Quantidade de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="users",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=75),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string", example="1"),
     *                         @OA\Property(property="name", type="string", example="Maria Silva"),
     *                         @OA\Property(property="email", type="string", example="maria@email.com"),
     *                         @OA\Property(property="cpf", type="string", example="12345678909"),
     *                         @OA\Property(property="createdAt", type="string", example="22/07/2025")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $dto = new BaseFilterDTO(
            search: $request->input('search'),
            field: $request->input('field'),
            direction: $request->input('direction'),
            perPage: $request->input('perPage')
        );

        $users = $this->service->getAll($dto);

        return response()->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/user/show/{id}",
     *     summary="Buscar usuário por ID",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="name", type="string", example="João da Silva"),
     *                 @OA\Property(property="email", type="string", example="joao@email.com"),
     *                 @OA\Property(property="cpf", type="string", example="12345678909"),
     *                 @OA\Property(property="createdAt", type="string", example="22/07/2025")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     * )
     */
    public function show(string $id): JsonResponse
    {
        $user = $this->service->findById($id);

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/user/store",
     *     summary="Registra um novo usuário",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","cpf"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="cpf", type="string", example="12345678909", description="CPF válido com 11 dígitos, apenas números"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Usuário criado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="O campo e-mail já está em uso.")),
     *                 @OA\Property(property="cpf", type="array", @OA\Items(type="string", example="O campo CPF já está em uso.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="O campo nome é obrigatório."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro inesperado ao registrar usuário.")
     *         )
     *     )
     * )
     */
    public function store(AuthUserRequest $request): JsonResponse
    {
        $dto = new UserFormDTO(
            id: null,
            name: $request->input('name'),
            email: $request->input('email'),
            cpf: $request->input('cpf'),
        );

        if ($this->service->store($dto)) {
            return response()->json(['success' => 'Usuário criado com sucesso!'], Response::HTTP_CREATED);
        }

        return response()->json(['error' => 'Falha ao criar usuário!'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Put(
     *     path="/api/user/update/{id}",
     *     summary="Atualiza um usuário",
     *     tags={"User"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do usuário a ser atualizado",
     *          required=true,
     *          @OA\Schema(type="string", example="1")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","cpf"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="cpf", type="string", example="12345678909", description="CPF válido com 11 dígitos, apenas números"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Usuário atualizado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="O campo e-mail já está em uso.")),
     *                 @OA\Property(property="cpf", type="array", @OA\Items(type="string", example="O campo CPF já está em uso.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="O campo nome é obrigatório."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro inesperado ao atualizar usuário.")
     *         )
     *     )
     * )
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $dto = new UserFormDTO(
            id: $id,
            name: $request->input('name'),
            email: $request->input('email'),
            cpf: $request->input('cpf'),
        );

        if ($this->service->update($dto)) {
            return response()->json(['success' => 'Usuário atualizado com sucesso!'], Response::HTTP_OK);
        }

        return response()->json(['error' => 'Falha ao atualizar usuário!'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/delete/{id}",
     *     summary="Excluir usuário",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário a ser excluído",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Usuário excluído com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Não foi possível excluir",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Não foi possível excluir!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        if($this->service->delete($id)) {
            return response()->json(['success' => 'Usuário excluído com sucesso!'], Response::HTTP_OK);
        }

        return response()->json(['error' => 'Não foi possível excluir!'], Response::HTTP_NOT_FOUND);
    }
}
