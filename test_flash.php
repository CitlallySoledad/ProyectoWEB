<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::create('/test', 'GET');
$response = $kernel->handle($request);

// Simular una sesión flash
session()->put('warning', 'Este es un mensaje de prueba');

echo "Mensaje flash guardado en sesión: " . session('warning') . "\n";
echo "Sesión ID: " . session()->getId() . "\n";
echo "¿Sesión tiene warning?: " . (session()->has('warning') ? 'SÍ' : 'NO') . "\n";
