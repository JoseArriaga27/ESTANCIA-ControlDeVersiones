<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio No Disponible</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background: #f5f7fb; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0; 
        }
        .error-card { 
            max-width: 500px; 
            width: 100%; 
            border: none; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            border-radius: 12px;
            background: white;
        }
        .btn-retry { 
            background-color: #0A2A43; 
            color: white; 
            border: none;
        } 
        .btn-retry:hover { 
            background-color: #071e31; 
            color: white; 
        }
    </style>
</head>
<body>
    <div class="card error-card p-4 text-center">
        <div class="card-body">
            <h1 class="display-1 text-danger mb-3"><i class="bi bi-wifi-off"></i></h1>
            
            <div class="alert alert-danger text-start" role="alert">
                <h4 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> ¡Error de Conexión!</h4>
                <p class="mb-2">No se pudo establecer comunicación con la base de datos.</p>
                <hr>
                <p class="mb-0 small">
                    <strong>Detalle técnico:</strong> <?= $error_message ?? 'Desconocido' ?>
                </p>
            </div>

            <p class="text-muted mt-4">
                El sistema no puede acceder a la información necesaria.
                Por favor, notifique al área de sistemas.
            </p>

            <a href="index.php" class="btn btn-retry px-4 py-2 mt-2 w-100 rounded-pill fw-bold">
                <i class="bi bi-arrow-clockwise"></i> Intentar Reconectar
            </a>
        </div>
    </div>
</body>
</html>