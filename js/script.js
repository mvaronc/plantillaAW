/**
 * 
 * @param {*} form. El nodo del formulario a validar
 * @param {*} arrayFields. Un objeto donde la clave es el nombre del campo a validar
 * y el valor es la expresión regular que debe cumplir el campo. 
 */

function validateForm(form,arrayFields) {
    // alert('arrayFields: ' + JSON.stringify(arrayFields));
    //Implement the form validation logic here
    for (const [fieldName, regex] of Object.entries(arrayFields)) {
        const field = form.elements[fieldName];
        if (field) {
            const value = field.value;
            let expression;
            if (regex instanceof RegExp) {
                expression = regex;
            } else if (typeof regex === 'string') {
                let pattern = regex;
                let flags = '';

                if (regex.startsWith('/')) {
                    const closingDelimiter = regex.lastIndexOf('/');
                    if (closingDelimiter > 0) {
                        pattern = regex.slice(1, closingDelimiter);
                        flags = regex.slice(closingDelimiter + 1);
                    }
                }

                try {
                    expression = new RegExp(pattern, flags);
                } catch (error) {
                    console.error(`Expresión regular no válida para el campo ${fieldName}:`, regex);
                    return false;
                }
            } else {
                console.error(`Restricción no válida para el campo ${fieldName}:`, regex);
                return false;
            }

            if (!expression.test(value)) {
                alert(`El campo ${fieldName} no cumple con el formato requerido.`);
                return false; // Prevent form submission
            }
        }
    }
    return true; // Allow form submission
}
