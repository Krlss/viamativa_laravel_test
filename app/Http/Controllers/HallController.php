<?php

namespace App\Http\Controllers;

use App\Http\Requests\HallCreateRequest;
use App\Http\Requests\HallUpdateRequest;
use App\Repositories\HallRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Halls",
 *     description="API Endpoints of halls"
 * )
 */

class HallController extends Controller
{
    private HallRepository $hallRepo;

    public function __construct(HallRepository $hallRepo)
    {
        $this->hallRepo = $hallRepo;
    }


    /**
     * @OA\Get(
     *     path="/api/halls",
     *     summary="Mostrar salas",
     *      tags={"Halls"},
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todas las salas."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function index()
    {
        $halls = $this->hallRepo->getAllHalls();
        return response()->json(['data' => $halls], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/halls/{id}",
     *     summary="Mostrar sala",
     *       tags={"Halls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la sala",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar sala por id."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sala no encontrada."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function show($id)
    {
        $hall = $this->hallRepo->getHallById($id);
        if (!$hall) return response()->json(['message' => 'Hall not found'], 404);
        return response()->json(['data' => $hall], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/halls",
     *     summary="Crear sala",
     *      tags={"Halls"},
     *     @OA\RequestBody(
     *         description="Datos de la sala",
     *         required=true,
     *           @OA\MediaType(
     *            mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                    property="name",
     *                   type="string"
     *                ),
     *                @OA\Property(
     *                   property="status",
     *                  type="boolean"
     *               ),
     *               @OA\Property(
     *                 property="id_movie",
     *               type="number",
     *              )
     *          )
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sala creada correctamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sala no encontrada."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function store(HallCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $hall = $this->hallRepo->createHall($input);

            if (isset($input['id_movie'])) $hall->movies()->sync($input['id_movie']);

            DB::commit();
            return response()->json(['data' => $hall], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/halls/{id}",
     *     summary="Actualizar sala",
     * tags={"Halls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la sala",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Datos de la sala",
     *         required=true,
     *          @OA\MediaType(
     *            mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                    property="name",
     *                   type="string"
     *                ),
     *                @OA\Property(
     *                   property="status",
     *                  type="boolean"
     *               ),
     *               @OA\Property(
     *                 property="id_movie",
     *               type="number",
     *              )
     *              )
     *        )
     *
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sala actualizada correctamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sala no encontrada."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function update($id, HallUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $findHall = $this->hallRepo->getHallById($id);
            if (!$findHall) return response()->json(['message' => 'Hall not found'], 404);

            $hall = $this->hallRepo->updateHall($input, $findHall);

            if (isset($input['id_movie'])) $hall->movies()->sync($input['id_movie']);

            DB::commit();
            return response()->json(['data' => $hall], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/halls/{id}",
     *     summary="Eliminar sala",
     * tags={"Halls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la sala",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sala eliminada correctamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sala no encontrada."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $findHall = $this->hallRepo->getHallById($id);
            if (!$findHall) return response()->json(['message' => 'Hall not found'], 404);

            $hall = $this->hallRepo->deleteHall($findHall);

            DB::commit();
            return response()->json(['data' => $hall], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/halls/review/{name}",
     *     summary="Mostrar si la película está llena o no",
     * tags={"Halls"},
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="Nombre de la sala",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sala encontrada."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sala no encontrada."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function getReviewHallByName(Request $request)
    {
        try {
            $name = $request->name;

            $findHall = $this->hallRepo->getHallByName($name);

            if (!$findHall) return response()->json(['message' => 'Hall not found'], 404);

            $hall_count = $this->hallRepo->getReviewHallByName($name);

            $msg = '';

            if ($hall_count < 3) $msg = 'Sala casi Vacía';
            else if ($hall_count >= 3 && $hall_count <= 5) $msg = 'Sala casi Llena';
            else $msg = 'Sala Llena';

            return response()->json(['data' => $msg], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
