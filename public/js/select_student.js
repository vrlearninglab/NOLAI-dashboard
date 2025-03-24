function filterStudents() {
    let input = document.getElementById('student_number').value.toLowerCase();
    let listItems = document.querySelectorAll('.student-item');

    listItems.forEach(function(item) {
        let studentNumber = item.textContent || item.innerText;
        if (studentNumber.toLowerCase().includes(input)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function fillInput(studentNumber) {
    document.getElementById('student_number').value = studentNumber;
    filterStudents(); // Filter meteen na het invullen
}