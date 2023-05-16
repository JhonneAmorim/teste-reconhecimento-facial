<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function saveFace(Request $request)
    {
        // Verifique se um arquivo de imagem foi enviado
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Crie um nome de arquivo único para a imagem
            $imageName = uniqid('face_') . '.jpg';

            // Salve a imagem no disco usando o facade Storage
            $imagePath = 'faces/' . $imageName;
            Storage::disk('public')->put($imagePath, $image);

            // Retorne o caminho relativo da imagem salva
            return response()->json(['imagePath' => $imagePath]);
        }

        // Retorne uma resposta de erro se nenhum arquivo de imagem for enviado
        return response()->json(['error' => 'Nenhum arquivo de imagem enviado.']);
    }
}
