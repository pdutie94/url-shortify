
function beforeSendRequest( form) {
    var formBtn = form.querySelector('button')
    formBtn.setAttribute("disabled", "disabled")
}
function afterSendRequest( form) {
    var formBtn = form.querySelector('button')
    formBtn.removeAttribute("disabled")
}

document.addEventListener('DOMContentLoaded', function(event) {
    var shortLinkForm = document.querySelector('.form-short_link')
    var saveShortLinkForm = document.querySelector('.form-save-short_link')
    var copyShortLinkBtn = saveShortLinkForm.querySelector('.short_link-copy')
    var shortLinkSaveBtn = document.querySelector('.short_link-save')

    var popupOptions = { 'escClose': false, 'bgClose': false }
    UIkit.modal('#short_link-popup').hide()

    if (shortLinkForm) {
        shortLinkForm.addEventListener('submit', function(e) {
            e.preventDefault()
            var method = 'POST'
            var url = 'includes/ajax.php'
            var xhr = new XMLHttpRequest()
            xhr.open(method, url, true)
            xhr.onreadystatechange = function () {
                if ( xhr.readyState == 4 && xhr.status == 200 ) {
                    var data = JSON.parse(xhr.response);
                    var domain = site_params.site_url
                    if ( shortLinkForm ) {
                        saveShortLinkForm.querySelector('input[name="short_url_id"]').value = data.short_id
                        saveShortLinkForm.querySelector('input[name="long_url"]').value = data.long_url
                        saveShortLinkForm.querySelector('.short_url').value = domain + '/' + data.short_id
                        UIkit.modal('#short_link-popup', popupOptions).show()
                    }
                    afterSendRequest(shortLinkForm)
                }
            }
            beforeSendRequest(shortLinkForm)
            var formData = new FormData(shortLinkForm)
            formData.append('action_name', 'generate_short_url_id')
            xhr.send(formData)
        })
        
    }
    if ( saveShortLinkForm ) { 
        shortLinkSaveBtn.onclick = function() {
            var method = 'POST'
            var url = 'includes/ajax.php'
            var xhr = new XMLHttpRequest()
            xhr.open(method, url, true)
            xhr.onreadystatechange = function () {
                if ( xhr.readyState == 4 && xhr.status == 200 ) {
                    var data = JSON.parse(xhr.response);
                    if (data.success) {
                        UIkit.modal('#short_link-popup').hide()
                        UIkit.notification({
                            message: 'Đã lưu thành công',
                            status: 'success',
                            pos: 'bottom-right',
                            timeout: 3000
                        });
                    } else {
                        UIkit.notification({
                            message: data.message,
                            status: 'danger',
                            pos: 'bottom-right',
                            timeout: 3000
                        });
                    }
                }
            }
            var formData = new FormData(saveShortLinkForm)
            formData.append('action_name', 'save_short_url_id')
            xhr.send(formData)
        }
        copyShortLinkBtn.onclick = function() {
            copyCmd(saveShortLinkForm.querySelector('input[name="short_url"]'))
        }
    }
})