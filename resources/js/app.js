import './bootstrap';


function numericOnly(element) {
    const inputValue = element.value.replace(/\D/g, "");
    element.value = inputValue;
}
window.numericOnly = numericOnly;

function teacherAction(action, teacher) {
    let config;

    if(action === 'Approve') {
        config = {
            title: action,
            icon: 'question',
            text: `Are you sure you want to ${action.toLowerCase()} this teacher?`,
            confirmButtonText: action,
            confirmButtonColor: '#198754',
            showCancelButton: true,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: async (message) => {
                let csrfToken = document.head.querySelector('meta[name="csrf-token"]');
                try {
                    const url = `/verify-teacher`;
                    const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken.content,
                            },
                            body: JSON.stringify({id: teacher, action: action, message: message})
                    });
                    if (!response.ok) {
                        return Swal.showValidationMessage(`
                            ${JSON.stringify(await response.json())}
                        `);
                    }
                    return response.json();
                } catch (error) {
                    Swal.showValidationMessage(`
                        Request failed: ${error}
                    `);
                }
            },
        }
    } else {
        config = {
            title: action,
            icon: 'question',
            text: `Are you sure you want to ${action.toLowerCase()} this teacher?`,
            input: 'textarea',
            inputLabel: 'Please provide your reason for your action.',
            confirmButtonText: action,
            confirmButtonColor: '#dc3545',
            showCancelButton: true,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: async (message) => {
                let csrfToken = document.head.querySelector('meta[name="csrf-token"]');
                try {
                    const url = `/verify-teacher`;
                    const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken.content,
                            },
                            body: JSON.stringify({id: teacher, action: action, message: message})
                    });
                    if (!response.ok) {
                        return Swal.showValidationMessage(`
                            ${JSON.stringify(await response.json())}
                        `);
                    }
                    return response.json();
                } catch (error) {
                    Swal.showValidationMessage(`
                        Request failed: ${error}
                    `);
                }
            },
        }
    }

    triggerSwal(config);
}
window.teacherAction = teacherAction;