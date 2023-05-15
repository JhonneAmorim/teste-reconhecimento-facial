<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class FacialRecognitionController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadImage(Request $request)
    {
        // Verifica se um arquivo foi enviado
        if ($request->hasFile('image')) {
            // Obtém a extensão do arquivo enviado
            $extension = $request->file('image')->getClientOriginalExtension();

            // Verifica se a extensão é um formato de imagem suportado
            $supportedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (!in_array(strtolower($extension), $supportedExtensions)) {
                // Retorne um erro ou faça o tratamento adequado para esse caso
                return response()->json(['error' => 'Formato de imagem não suportado.']);
            }

            // Salva a imagem no diretório temporário
            $imagePath = Storage::putFile('temp', $request->file('image'));

            // Carrega a imagem com a biblioteca Intervention Image
            $image = Image::make(public_path($imagePath));

            // Restante do código para processar a imagem...

            // Exemplo de redimensionamento da imagem
            $resizedImage = $image->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Salva a imagem redimensionada
            $resizedImagePath = 'storage/' . $resizedImage->save('temp/resized.jpg')->basename();

            // Exibe a imagem redimensionada
            return view('result', ['imagePath' => $resizedImagePath]);
        } else {
            // Retorne um erro ou faça o tratamento adequado para caso nenhum arquivo tenha sido enviado
            return response()->json(['error' => 'Nenhum arquivo foi enviado.']);
        }
    }

    public function showCameraView()
    {
        return View::make('camera');
    }
}
