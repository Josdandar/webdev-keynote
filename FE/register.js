const form = document.getElementById('registrationForm');

// Escuchar el evento de envío del formulario
form.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

    // Obtener los valores de los inputs
    const name = document.getElementById('name').value;
    const age = parseInt(document.getElementById('age').value, 10);
    const email = document.getElementById('email').value;

    // Crear un objeto con los datos del formulario
    const data = {
        name: name,
        age: age,
        email: email
    };
    console.log(data)
    // Enviar los datos al archivo PHP usando fetch
    fetch('../BE/insert.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data) // Convertir el objeto a JSON y enviarlo
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(result => {
        // Mostrar el resultado del servidor en la consola
        console.log('Respuesta del servidor:', result);
        alert(result.mensaje || 'Registro exitoso');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un problema al registrar el usuario.');
    });

    // Limpiar el formulario después de enviar los datos
    form.reset();
});
