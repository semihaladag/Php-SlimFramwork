const API_BASE_URL = '/eduji/public'; 
const STUDENTS_ENDPOINT = '/students';

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('updateForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Formun varsayılan davranışını engelle

        // hidden input id
        const studentId = document.getElementById('updateStudentId').value;

        submitUpdateForm(event, studentId);
    });


    fillTable();
});


function fillTable() {
    fetch(API_BASE_URL + STUDENTS_ENDPOINT)
        .then(response => response.json())
        .then(students => {
            const tableBody = document.getElementById('studentsTableBody');
            tableBody.innerHTML = ''; // Önceki içeriği temizle

            students.forEach(student => {
                const row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${student.id}</td>
                    <td>${student.tc}</td>
                    <td>${student.ad}</td>
                    <td>${student.soyad}</td>
                    <td>${student.okul_adi}</td>
                    <td>${student.okul_no}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal">
                            Güncelle
                        </button>
                        <button onclick="deleteStudent(${student.id})" class="btn btn-danger btn-sm">Sil</button>

                    </td>
                `;
            });
        })
        .catch(error => console.error('Error fetching students:', error));
}

function submitUpdateForm(event, studentId) {
    event.preventDefault(); 
    const updateTc = document.getElementById('updateId').value;
    const updateId = document.getElementById('updateTc').value;
    const updateAd = document.getElementById('updateAd').value;
    const updateSoyad = document.getElementById('updateSoyad').value;
    const updateOkulAdi = document.getElementById('updateOkulAdi').value;
    const updateOkulNo = document.getElementById('updateOkulNo').value;

    /*   -- ---  ??? ----- ---*/
    const requestData = {
        id: updateId,
        tc: updateTc,
        ad: updateAd,
        soyad: updateSoyad,
        okul_adi: updateOkulAdi,
        okul_no: updateOkulNo
    };


    console.log('Gönderilen Veriler:', JSON.stringify(requestData));

fetch(API_BASE_URL + '/students/update/' + studentId, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(requestData)
})
.then(response => {
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
        return response.json();
    } else {
        const errorMessage = `Invalid JSON response. Content-Type: ${contentType}`;
        console.error(errorMessage);
        throw new Error(errorMessage);
    }
})

    .then(data => {
        console.log('Güncelleme başarılı:', data);
        fillTable();
    })
    .catch(error => {
        console.error('Güncelleme hatası:', error.message);
    
        if (error.response) {
            if (error.response.headers.get('content-type').includes('application/json')) {
                error.response.json().then(json => console.error(json));
            } else {
                error.response.text().then(text => console.error(text));
            }
        } else {
            console.error('Response not received');
        }
    
    })
    .finally(() => {
        const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
        updateModal.hide();
    });
}

function deleteStudent(studentId) {
    

    const isConfirmed = confirm("Bu öğrenciyi silmek istediğinizden emin misiniz?");
    
    if (isConfirmed) {
        fetch(API_BASE_URL + '/students/delete/' + studentId, {
            method: 'DELETE'
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                const errorMessage = `Invalid JSON response. Content-Type: ${contentType}`;
                console.error(errorMessage);
                throw new Error(errorMessage);
            }
        })
        .then(data => {
            console.log('Silme başarılı:', data);
            fillTable();
        })
        .catch(error => {
            console.error('Silme hatası:', error.message);
        
            if (error.response) {
                if (error.response.headers.get('content-type').includes('application/json')) {
                    error.response.json().then(json => console.error(json));
                } else {
                    error.response.text().then(text => console.error(text));
                }
            } else {
                console.error('Response not received');
            }
        
        });
    }
}
