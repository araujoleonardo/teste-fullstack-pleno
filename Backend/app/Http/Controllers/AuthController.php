<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registra um novo usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","cpf","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="cpf", type="string", example="12345678909", description="CPF válido com 11 dígitos, apenas números"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso e token JWT retornado",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="O campo e-mail já está em uso.")),
     *                 @OA\Property(property="cpf", type="array", @OA\Items(type="string", example="O campo CPF já está em uso.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="O campo senha é obrigatório."))
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
    public function register(AuthUserRequest $request): JsonResponse
    {
        try {
            $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
            $user = User::create([
                'name' => Str::upper($request->name),
                'email' => $request->email,
                'cpf' => $cpf,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json($this->respondWithToken($token)->getData(true), Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json(
                ['error' => 'Erro ao registrar usuário.', 'message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autentica um usuário e retorna um token JWT",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido. Retorna o token JWT",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Credenciais inválidas"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação nos campos enviados",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="O campo email é obrigatório.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="O campo senha deve ter no mínimo 6 caracteres."))
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'errors' => ['email' => ['Credenciais inválidas']]
                ], Response::HTTP_UNAUTHORIZED);
            }

            return $this->respondWithToken($token);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao tentar autenticar.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Faz logout do usuário autenticado",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout realizado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou não fornecido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token não fornecido")
     *         )
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout realizado com sucesso'], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh-token",
     *     summary="Atualiza (refresh) o token JWT",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token JWT atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Não foi possível atualizar o token")
     *         )
     *     )
     * )
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = JWTAuth::refresh();
            return $this->respondWithToken($newToken);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não foi possível atualizar o token'], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user-auth",
     *     summary="Retorna os dados do usuário autenticado",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou não fornecido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token não fornecido")
     *         )
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], Response::HTTP_OK);
    }

    public function validateToken(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['valid' => true], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['valid' => false], Response::HTTP_UNAUTHORIZED);
        }
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }
}
