<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Öğrenci listesi için endpoint
$app->get('/students', function (Request $request, Response $response) {
    // Burada veritabanından öğrenci listesini çekme işlemini yapın
    // Örnek: $students = $yourDatabase->fetchAllStudents();

    // Daha sonra $students'i JSON formatına dönüştürün ve response olarak gönderin
    $response->getBody()->write(json_encode($students));

    return $response->withHeader('Content-Type', 'application/json');
});

// Sayfalama eklemek için öğrenci listesi için endpoint
$app->get('/students/page/{page}', function (Request $request, Response $response, array $args) {
    $page = $args['page'];

    // Burada belirli bir sayfadaki öğrenci listesini çekme işlemini yapın
    // Örnek: $students = $yourDatabase->fetchStudentsByPage($page);

    // Daha sonra $students'i JSON formatına dönüştürün ve response olarak gönderin
    $response->getBody()->write(json_encode($students));

    return $response->withHeader('Content-Type', 'application/json');
});

// Öğrenci güncelleme için endpoint
$app->route(['PUT'], '/students/update/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    // Burada belirli bir öğrencinin bilgilerini güncelleme işlemini yapın
    // Örnek: $yourDatabase->updateStudent($id, $data);

    $db = new Database(); // Database sınıfından bir örnek oluşturun
    $db->updateStudent($id, $data); // Öğrenci güncelleme işlemini yapın

    // Güncelleme işlemi başarıyla gerçekleştiğinde JSON yanıtı gönderin
    $response->getBody()->write(json_encode(['success' => true]));

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

// Öğrenci silme için endpoint
$app->delete('/students/delete/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    // Burada belirli bir öğrenciyi silme işlemini yapın
    // Örnek: $yourDatabase->deleteStudent($id);

    return $response->withHeader('Content-Type', 'application/json');
});
