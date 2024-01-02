function validateLoginForm() {
    var form = document.querySelector('.form-login')
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault()
            var userName = form.querySelector('.username')
            var userNameFormControl = userName.closest('.form-control')
            var userNameErrorMessage = userNameFormControl.querySelector('.field-message')
            var password = form.querySelector('.password')
            var passwordFormControl = password.closest('.form-control')
            var passwordErrorMessage = passwordFormControl.querySelector('.field-message')

            if ( userName.value === "" || password.value === "" ) {
                if ( userName.value === "" ) {
                    userName.classList.add('uk-form-danger')
                    if ( userNameErrorMessage ) {
                        userNameErrorMessage.remove()
                    }

                    var usernameMessage = document.createElement('div')
                    usernameMessage.classList.add('field-message', 'uk-text-danger')
                    usernameMessage.innerHTML = 'Tên đăng nhập không được để trống!'
                    userNameFormControl.appendChild(usernameMessage)
                } else {
                    userName.classList.add('uk-form-success')
                    userName.classList.remove('uk-form-danger')
                    if ( userNameErrorMessage ) {
                        userNameErrorMessage.remove()
                    }
                }
                if ( password.value === "" ) {
                    password.classList.add('uk-form-danger')
                    if ( passwordErrorMessage ) {
                        passwordErrorMessage.remove()
                    }

                    var passwordMessage = document.createElement('div')
                    passwordMessage.classList.add('field-message', 'uk-text-danger')
                    passwordMessage.innerHTML = 'Mật khẩu không được để trống!'
                    passwordFormControl.appendChild(passwordMessage)
                } else {
                    password.classList.add('uk-form-success')
                    password.classList.remove('uk-form-danger')
                    if ( passwordErrorMessage ) {
                        passwordErrorMessage.remove()
                    }
                }
                return
            } else {
                var fieldMessageList = form.querySelectorAll('.field-message')
                fieldMessageList.forEach(function(fieldMessage) {
                    fieldMessage.remove();
                })
                userName.classList.remove('uk-form-danger')
                password.classList.remove('uk-form-danger')
                form.submit();
            }
        })
    }
}

document.addEventListener('DOMContentLoaded', function(event) {
    validateLoginForm()
})