<?php

class Database
{
    private $db;

    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'ogrenci';
        $username = 'root';
        $password = '';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Bağlantı hatası: " . $e->getMessage();
            die();
        }
    }

    public function fetchAllStudents()
    {
        $stmt = $this->db->query("SELECT * FROM ogrenciler");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchStudentsByPage($page)
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM ogrenciler LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function updateStudent($id, $data)
{
    $query = "UPDATE ogrenciler SET
                id =id, /* */
                tc = :tc,
                ad = :ad,
                soyad = :soyad,
                okul_adi = :okul_adi,
                okul_no = :okul_no
              WHERE id = 1";

    $params = [
        'id' => $id,
        'tc' => $data['tc'],
        'ad' => $data['ad'],
        'soyad' => $data['soyad'],
        'okul_adi' => $data['okul_adi'],
        'okul_no' => $data['okul_no']
    ];

    $statement = $this->db->prepare($query);
    $statement->execute($params);
}

    public function deleteStudent($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ogrenciler WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
