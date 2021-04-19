<?php

namespace App\Http\Controllers;

use App\Models\Autores;
use App\Models\Libros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LibrosController extends Controller
{
    /**
     * Funcion que permite crear un nuevo libro en la BD
     *
     */
    public function store(Request $request)
    {
        //--Valdiar que exista el isbn--
        if (! isset($request['isbn'])) {
            return json_encode(['status' => 0, 'message' => 'El Codigo ISBN es obligatorio']);
        } else {
            //--validar que sea numero entero positivo--
            if (! ctype_digit($request['isbn'])) {
                return json_encode(['status' => 0, 'message' => 'El Codigo ISBN debe ser numerico.']);
            } else {
                //--realizar consumo de api open library--
                $ISBN = $request['isbn'];
                $url = http::get('https://openlibrary.org/api/books?bibkeys=ISBN:'.$ISBN.'&jscmd=data&format=json');
                $campos_array = $url->json();
                //--Valdidar si existe codigo isbn en la base de api de openlibrary--
                if (! isset($campos_array['ISBN:'.$ISBN])) {
                    return json_encode(['status' => 0, 'message' => 'El Codigo ISBN No fue encontrado.']);
                } else {
                    $var['title'] = ($campos_array['ISBN:'.$ISBN]['title']);
                    //--Validar que el registro cuente con una imagen de lo contrario asignar undefined--
                    if (isset($campos_array['ISBN:'.$ISBN]['cover']['large'])) {
                        $var['cover_large'] = ($campos_array['ISBN:'.$ISBN]['cover']['large']);
                    } else {
                        $var['cover_large'] = 'undefined';
                    }
                    //--Validar que el registro cuente con un autor como minimo. de lo contrario asignar undefined--
                    if (isset($campos_array['ISBN:'.$ISBN]['authors'])) {
                        $var['authors'] = ($campos_array['ISBN:'.$ISBN]['authors']);
                    } else {
                        $var['authors'] = 'undefined';
                    }
                    //Verificar si ya existe un libro con el mismo isbn en la base de datos
                    $validador = Libros::where('isbn', $ISBN)->get();

                    if ($validador) {
                        return json_encode([
                            'status' => 0,
                            'message' => 'Error al guardar el libro. ya se encuentra registrado',
                        ]);
                    }

                    $book = Libros::create([
                        'isbn' => $ISBN,
                        'title' => $var['title'],
                        'cover_large' => $var['cover_large'],
                    ]);
                    //--Validar que el registro cuente con autores de lo contrario omite inster tabla autores--
                    if ($var['authors'] != 'undefined') {
                        foreach ($var['authors'] as $author) {
                            Autores::create([
                                'book_id' => $book->id,
                                'author' => $author['name'],
                            ]);
                        }
                    }
                    // Validar si se guarda el registro con exito en la base de datos.
                    if ($book->id) {
                        return json_encode(['status' => 1, 'message' => 'Exito al Guardar.']);
                    } else {
                        return json_encode(['status' => 0, 'message' => 'Error al Guardar.']);
                    }
                }
            }
        }
    }

    /**
     * Funcion que permite eliminar un libro en la BD
     *
     */
    public function destroy(Request $request)
    {
        //--Valdiar que exista el isbn--
        if (! isset($request['isbn'])) {
            return json_encode(['status' => 0, 'message' => 'El Codigo ISBN es obligatorio']);
        } else {
            if (! ctype_digit($request['isbn'])) {
                return json_encode(['status' => 0, 'message' => 'El Codigo ISBN debe ser numerico.']);
            }else{
                $id_book = Libros::where('isbn', $request['isbn'])->get();

                if($id_book){
                    //tocaria buscar los ids de los autores y luego si eliminar el principal 
                    //$autores= Autores::where('book_id',$id_book[0]['id'])->get();
                    //$autores->tasks()->delete();
                    //$autores->delete();
                    //Autores::destroy()->where('book_id',$id_book[0]['id']);
                    Libros::destroy($id_book[0]['id']);

                    return json_encode(['status' => 1, 'message' => 'Exito al eliminar el libro.']);
                }else{
                    return json_encode(['status' => 0, 'message' => 'El Codigo ISBN No a sido encontrado en la base de datos.']);
                }



            }
        }
    }

    /**
     * Funcion que permite listar los libros en la BD
     *
     */
    public function show()
    {
        $books = Libros::with('autores')->get();

        return $books;
    }

    /**
     * Funcion que permite ver en detalles un  libro en la BD
     *
     */
    public function detalis()
    {
        $books = Libros::with('autores')->get();

        return $books;
    }
}
