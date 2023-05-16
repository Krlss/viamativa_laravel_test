<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieCreateRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Repositories\MovieRepository;
use App\Models\Movie;
use App\Repositories\HallRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Movies",
 *     description="API Endpoints of Movies"
 * )
 */
class MovieController extends Controller
{

    private MovieRepository $movieRepo;
    private HallRepository $hallRepo;

    public function __construct(MovieRepository $movieRepo, HallRepository $hallRepo)
    {
        $this->movieRepo = $movieRepo;
        $this->hallRepo = $hallRepo;
    }


    /**
     * @OA\Get(
     *     path="/api/films",
     *     summary="Mostrar peliculas",
     *      tags={"Movies"},
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todas las peliculas."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */

    public function index()
    {
        $movies = $this->movieRepo->getAllMovies();
        return response()->json(['data' => $movies], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/films/{id}",
     *     summary="Mostrar pelicula",
     *       tags={"Movies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la pelicula",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar pelicula por id."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelicula no encontrada."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */

    public function show($id)
    {
        $movie = $this->movieRepo->getMovieById($id);
        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);
        return response()->json(['data' => $movie], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/films",
     *     summary="Crear pelicula",
     *      tags={"Movies"},
     *     @OA\RequestBody(
     *         description="Datos de la pelicula",
     *         required=true,
     * @OA\MediaType(
     *            mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                    property="name",
     *                   type="string"
     *                ),
     *                @OA\Property(
     *                   property="duration",
     *                  type="number"
     *               ),
     *               @OA\Property(
     *                 property="id_hall",
     *               type="number",
     *              )
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pelicula creada correctamente."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validacion."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function store(MovieCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $movie = $this->movieRepo->createMovie($input);

            if (isset($input['id_hall']))  $movie->movieHalls()->sync($input['id_hall']);

            DB::commit();
            return response()->json(['data' => $movie], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/films/{id}",
     *     summary="Actualizar pelicula",
     *      tags={"Movies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la pelicula",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Datos de la pelicula",
     *         required=true,
     * @OA\MediaType(
     *            mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                    property="name",
     *                   type="string"
     *                ),
     *                @OA\Property(
     *                   property="duration",
     *                  type="number"
     *               ),
     *               @OA\Property(
     *                 property="id_hall",
     *               type="number",
     *              )
     *              )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pelicula actualizada correctamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelicula no encontrada."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validacion."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function update($id, MovieUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $findMovie = $this->movieRepo->getMovieById($id);
            if (!$findMovie) return response()->json(['message' => 'Movie not found'], 404);

            $movie = $this->movieRepo->updateMovie($input, $findMovie);

            if (isset($input['id_hall'])) $movie->movieHalls()->sync($input['id_hall']);

            DB::commit();
            return response()->json(['data' => $movie], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/films/{id}",
     *     summary="Eliminar pelicula",
     *      tags={"Movies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la pelicula",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pelicula eliminada correctamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelicula no encontrada."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $findMovie = $this->movieRepo->getMovieById($id);
            if (!$findMovie) return response()->json(['message' => 'Movie not found'], 404);

            $movie = $this->movieRepo->deleteMovie($findMovie);

            DB::commit();
            return response()->json(['data' => $movie], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/films/{name}/{id_hall}",
     *     summary="Obtener pelicula por nombre y id de sala",
     *      tags={"Movies"},
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="Nombre de la pelicula",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_hall",
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
     *         description="Pelicula encontrada."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelicula no encontrada."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function getMovieByNameAndIdHall(Request $request)
    {
        $name = $request->name;

        $findMovie = $this->movieRepo->findByName($name);
        if (!$findMovie) return response()->json(['message' => 'Movie not found'], 404);

        $id_hall = $request->id_hall;

        $findHall = $this->hallRepo->getHallById($id_hall);
        if (!$findHall) return response()->json(['message' => 'Hall not found'], 404);


        $movie = $this->movieRepo->findByNameAndId_hall($name, $id_hall);
        return response()->json(['data' => $movie], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/films/{date_published}",
     *     summary="Obtener peliculas por fecha de publicacion",
     *      tags={"Movies"},
     *     @OA\Parameter(
     *         name="date_published",
     *         in="path",
     *         description="Fecha de publicacion de la pelicula",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Peliculas encontradas."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Peliculas no encontradas."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */

    public function getCountMoviesByDatePublished(Request $request)
    {
        $date_published = $request->date_published;

        $count = $this->movieRepo->countMoviesByDatePublished($date_published);
        return response()->json(['data' => $count], 200);
    }
}
