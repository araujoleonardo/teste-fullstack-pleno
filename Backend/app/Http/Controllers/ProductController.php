<?php

namespace App\Http\Controllers;

use App\DTO\FormDTO\ProductFormDTO;
use App\DTO\PaginationFilter\BaseFilterDTO;
use App\Http\Requests\ProductFormRequest;
use App\Services\Product\IProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    protected IProductService $service;

    public function __construct(IProductService $productService)
    {
        $this->service = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/product/{id}",
     *     summary="Listar produtos de um usuario com filtro e paginação",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do usuario dono dos produtos",
     *          required=true,
     *          @OA\Schema(type="string", example="1")
     *      ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Texto de busca (por nome, preco, etc.)",
     *         required=false,
     *         @OA\Schema(type="string", example="Teclado ABNT")
     *     ),
     *     @OA\Parameter(
     *         name="field",
     *         in="query",
     *         description="Campo para ordenação (ex: name, price)",
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
     *         description="Lista de produtos retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="products",
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
     *                         @OA\Property(property="name", type="string", example="Teclado ABNT"),
     *                         @OA\Property(property="price", type="float", example="10.5"),
     *                         @OA\Property(property="description", type="string", example="Teclado Portugues Brasil"),
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
    public function index(Request $request, int $id): JsonResponse
    {
        $dto = new BaseFilterDTO(
            search: $request->input('search'),
            field: $request->input('field'),
            direction: $request->input('direction'),
            perPage: $request->input('perPage')
        );

        $products = $this->service->getAll($dto, $id);

        return response()->json(['products' => $products], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/product/store/{id}",
     *     summary="Registra um novo produto",
     *     tags={"Product"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do usuario dono do produto",
     *          required=true,
     *          @OA\Schema(type="string", example="1")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price"},
     *             @OA\Property(property="name", type="string", example="Teclado ABNT"),
     *             @OA\Property(property="price", type="string", example="R$ 10,50"),
     *             @OA\Property(property="description", type="string", example="Teclado Portugues Brasil"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Produto criado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="O campo nome é obrigatorio.")),
     *                 @OA\Property(property="price", type="array", @OA\Items(type="string", example="O campo preco é obrigatorio.")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro inesperado ao registrar produto.")
     *         )
     *     )
     * )
     */
    public function store(ProductFormRequest $request, int $id): JsonResponse
    {
        $dto = new ProductFormDTO(
            id: null,
            name: $request->input('name'),
            price: $request->input('price'),
            description: $request->input('description'),
        );

        if ($this->service->store($dto, $id)) {
            return response()->json(['success' => 'Produto criado com sucesso!'], Response::HTTP_CREATED);
        }

        return response()->json(['error' => 'Falha ao criar produto!'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Put(
     *     path="/api/product/update/{id}",
     *     summary="Atualiza um produto",
     *     tags={"Product"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do produto a ser atualizado",
     *          required=true,
     *          @OA\Schema(type="string", example="1")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price"},
     *             @OA\Property(property="name", type="string", example="Teclado ABNT"),
     *             @OA\Property(property="price", type="string", example="R$ 10,50"),
     *             @OA\Property(property="description", type="string", example="Teclado Portugues Brasil"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Produto atualizado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="O campo nome é obrigatorio.")),
     *                 @OA\Property(property="price", type="array", @OA\Items(type="string", example="O campo preco é obrigatorio.")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro inesperado ao atualizar produto.")
     *         )
     *     )
     * )
     */
    public function update(ProductFormRequest $request): JsonResponse
    {
        $dto = new ProductFormDTO(
            id: $request->input('id'),
            name: $request->input('name'),
            price: $request->input('price'),
            description: $request->input('description'),
        );

        if ($this->service->update($dto)) {
            return response()->json(['success' => 'Produto atualizado com sucesso!'], Response::HTTP_OK);
        }

        return response()->json(['error' => 'Falha ao atualizar produto!'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Delete(
     *     path="/api/product/delete/{id}",
     *     summary="Excluir produto",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do produto a ser excluído",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Produto excluído com sucesso!")
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
            return response()->json(['success' => 'Produto excluído com sucesso!'], Response::HTTP_OK);
        }

        return response()->json(['error' => 'Não foi possível excluir!'], Response::HTTP_NOT_FOUND);
    }
}
