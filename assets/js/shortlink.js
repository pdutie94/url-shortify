
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
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax_generate_short_url.php", true)
            xhr.onload = function() {
                if ( xhr.readyState == 4 && xhr.status == 200 ) {
                    var data = JSON.parse(xhr.response);
                    var domain = 'http://url-shortify.test/'
                    if ( shortLinkForm ) {
                        saveShortLinkForm.querySelector('input[name="short_url_id"]').value = data.short_id
                        saveShortLinkForm.querySelector('input[name="long_url"]').value = data.long_url
                        saveShortLinkForm.querySelector('.short_url').value = domain + data.short_id
                        UIkit.modal('#short_link-popup', popupOptions).show()
                    }
                    afterSendRequest(shortLinkForm)
                }
            }
            beforeSendRequest(shortLinkForm)
            var formData = new FormData(shortLinkForm)
            console.log( formData)
            xhr.send(formData)
        })
        
    }
    if ( saveShortLinkForm ) { 
        shortLinkSaveBtn.onclick = function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax_save_url.php", true)
            xhr.onload = function() {
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
            xhr.send(formData)
        }
        copyShortLinkBtn.onclick = function() {
            var copyText = saveShortLinkForm.querySelector('input[name="short_url"]')
            copyText.select()
            document.execCommand("copy")
        }
    }
})