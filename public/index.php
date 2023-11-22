<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Autoloader'ı kullanarak Database sınıfını dahil et
require __DIR__ . '/../src/routes/Database.php';



// Slim uygulama örneğini oluştur
$app = AppFactory::create();

// Baz yolunu ayarla
$app->setBasePath('/eduji/public');

// Öğrenci listesi için endpoint
$app->get('/students', function (Request $request, Response $response) {
    $db = new Database();
    $students = $db->fetchAllStudents();

    $response->getBody()->write(json_encode($students));

    return $response->withHeader('Content-Type', 'application/json');
});

// Sayfalama eklemek için öğrenci listesi için endpoint
$app->get('/students/page/{page}', function (Request $request, Response $response, array $args) {
    $db = new Database();
    $page = $args['page'];
    $students = $db->fetchStudentsByPage($page);

    $response->getBody()->write(json_encode($students));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/students/update/{id}', function (Request $request, Response $response, array $args) {
    $db = new Database();
    $id = $args['id'];
    $data = $request->getParsedBody();

    // Gerekli parametreleri kontrol et
    if (isset($data['ad']) && isset($data['soyad']) && isset($data['okul_adi']) && isset($data['okul_no'])) {
        // Gerekli parametreler varsa, güncelleme işlemini gerçekleştir
        $db->updateStudent($id, $data);

        // Başarılı yanıt
        return $response->withJson(['success' => true]);
    } else {
        // Gerekli parametreler eksikse, hata yanıtı
        return $response->withJson(['error' => 'Missing required parameters'], 400);
    }
});

// Öğrenci silme için endpoint
$app->delete('/students/delete/{id}', function (Request $request, Response $response, array $args) {
    $db = new Database();
    $id = $args['id'];
    $db->deleteStudent($id);

    return $response->withHeader('Content-Type', 'application/json');
});

// Uygulamayı çalıştır
$app->run();
