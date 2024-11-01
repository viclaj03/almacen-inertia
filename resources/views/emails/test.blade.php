<!DOCTYPE html>
<html>
<head>
    <title>Recibistes imagen </title>
</head>
<body>
    <h1><strong>Ver Imagen:</strong> 
        @foreach ($images as $image)
        <a href="{{ url('/images/' . $image->id) }}">Haga clic aquí para ver la imagen {{$image->name}}</a><br><br>
        @endforeach
        
     </h1>

     @foreach ($images as $image)
     <a href="{{ url('/images/' . $image->id) }}">
        <img src="{{$message->embed(storage_path('app/public/imagesPost/' . $image->imagen)) }}" width="500px">
     </a>
     @endforeach
    <p>¡Gracias por usar nuestra aplicación!</p>
</body>
</html>
