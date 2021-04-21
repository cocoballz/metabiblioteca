<?php

namespace App\Http\Controllers;

use App\Http\Resources\book;
use App\Http\Resources\BookCollection;
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

                    if (count($validador) != 0) {
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
            //--Validar si el codigo caracteres datos no numericos--
            if (! ctype_digit($request['isbn'])) {
                return json_encode(['status' => 0, 'message' => 'El Codigo ISBN debe ser numerico.']);
            } else {
                $id_book = Libros::where('isbn', $request['isbn'])->get();
                if (count($id_book) > 0) {
                    $author = Autores::where('book_id', $id_book[0]['id']);
                    $author->delete();
                    Libros::destroy($id_book[0]['id']);

                    return json_encode(['status' => 1, 'message' => 'Exito al eliminar el libro.']);
                } else {
                    return json_encode([
                        'status' => 0,
                        'message' => 'El Codigo ISBN No a sido encontrado en la base de datos.',
                    ]);
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
        $books = Libros::paginate(2);

        return new BookCollection($books);
    }

    /**
     * Funcion que permite ver en detalles un  libro en la BD
     *
     */
    public function detalis($isbn = '')
    {

        //--Validar si el codigo caracteres datos no numericos--
        if (! ctype_digit($isbn)) {
            return json_encode(['status' => 0, 'message' => 'El Codigo ISBN debe ser numerico.']);
        } else {

            $books = Libros::with('autores')->where('isbn', $isbn)->get();

            //return $books;
            if (count($books) > 0) {
                $var = '
                    <Libros>
                        <Libro>
                            <Titulo>'.$books[0]['title'].'</Titulo>
                            <Isbn>'.$books[0]['isbn'].'</Isbn>
                            <Autores>
                ';
                foreach ($books[0]['autores'] as $Autor) {
                    $var = $var.'<autor>'.$Autor->author.'</autor>';
                }
                $var = $var.'
                            </Autores>
                        </libro>
                    </Libros>
                ';

                $xml = <<<XML
            <?xml version="1.0" encoding="utf-8" standalone="yes" ?>
            {$var}
            XML;
            } else {
                $xml = <<<XML
            <?xml version="1.0" encoding="utf-8" standalone="yes" ?>
            <Libros>
                Error Libro no encontrado
            </Libros>
            XML;
            }

            //print_r($data);

            return response()->xml($xml);

            return response()->xml($data, 200, [], 'libros', 'utf-8');

            return \Facade\FlareClient\Http\Response::xml($books);
        }
    }
}
