function beforeSendRequest( form) {
	var formBtn = form.querySelector( 'button' )
	formBtn.setAttribute( "disabled", "disabled" )
}
function afterSendRequest( form) {
	var formBtn = form.querySelector( 'button' )
	formBtn.removeAttribute( "disabled" )
}

function addNewRow( data ) {
	var editLink = site_params.site_url + '/index.php?controller=links&action=edit&lid=' + data.short_id;
	var statsLink = site_params.site_url + '/index.php?controller=links&action=stats&lid=' + data.short_id;

	var tableBody = document.querySelector( '.table-list tbody' )
	var row       = tableBody.insertRow( 0 )
	row.classList.add( 'uk-animation-fade' )
	var c1        = row.insertCell( 0 )
	var c2        = row.insertCell( 1 )
	var c3        = row.insertCell( 2 )
	var c4        = row.insertCell( 3 )
	var c5        = row.insertCell( 4 )
	c1.classList.add( 'link-id' )
	c2.classList.add( 'uk-text-nowrap' )
	c3.classList.add( 'uk-text-truncate' )
	c4.classList.add( 'uk-text-right' )
	c5.classList.add( 'uk-text-nowrap' )

	c1.innerHTML = data.short_id
	c2.innerHTML = '<div class="link-title" contenteditable="true">'+data.title+'</div>'
	c3.innerHTML = '<div class="uk-inline uk-width-1-1"><input value="' + data.short_url + '" class="uk-input uk-form-medium short_url" style="padding-right: 40px" type="text" readonly=""><a class="short_link-copy uk-form-icon uk-form-icon-flip" uk-tooltip="title: Sao chép; pos: bottom-right" tabindex="0"><span uk-icon="icon: copy" class="uk-icon"></span></a></div>'
	c4.innerHTML = data.created_at
	c5.innerHTML = '<div class="uk-flex uk-flex-right" style="gap: 12px;"><a uk-tooltip="title: Chỉnh sửa thông tin" href="'+editLink+'" class="uk-icon-link" uk-icon="file-edit"></a><a uk-tooltip="title: Xem thống kê chi tiết" href="'+statsLink+'" class="uk-icon-link" uk-icon="bolt"></a><a uk-tooltip="title: Xóa link" href="#" class="uk-icon-link" uk-icon="trash"></a></div>';

	row.querySelector( '.short_link-copy' ).addEventListener(
		'click',
		function () {
			copyCmd( row.querySelector( 'input.short_url' ) )
		}
	)
}

function contentChanged(div, index) {
	return div.innerHTML !== previousContents[index];
}

function saveLinkTitle() {
	var editableTitleEls = document.querySelectorAll('.link-title')

	editableTitleEls.forEach(function (editableTitleEl, index) {
		previousContents[index] = editableTitleEl.innerHTML;
		editableTitleEl.onblur = function () {
			if (contentChanged(editableTitleEl, index)) {
				var newVal = editableTitleEl.innerHTML
				var shortID = editableTitleEl.closest('tr').querySelector('td.link-id').innerHTML
				var method             = 'POST'
				var url                = 'includes/ajax.php'
				var xhr                = new XMLHttpRequest()
				xhr.open( method, url, true )
				xhr.onreadystatechange = function () {
					if ( xhr.readyState == 4 && xhr.status == 200 ) {
						var res = JSON.parse( xhr.response );
						if (res.success) {
							UIkit.notification(
								{
									message: 'Đã lưu thành công',
									status: 'success',
									pos: 'bottom-right',
									timeout: 3000
								}
							);
							previousContents[index] = newVal;
						} else {
							UIkit.notification(
								{
									message: res.message,
									status: 'danger',
									pos: 'bottom-right',
									timeout: 3000
								}
							);
						}
					}
				}
				var formData = new FormData()
				formData.append( 'action_name', 'save_short_link_title' )
				formData.append( 'short_id', shortID )
				formData.append( 'title', newVal )
				xhr.send( formData )
			}
		}
		
	});
}

var previousContents = {}
document.addEventListener(
	'DOMContentLoaded',
	function (event) {
		saveLinkTitle();

		var shortLinkForm     = document.querySelector( '.form-short_link' )
		var saveShortLinkForm = document.querySelector( '.form-save-short_link' )
		var copyShortLinkBtn  = saveShortLinkForm.querySelector( '.short_link-copy' )
		var shortLinkSaveBtn  = document.querySelector( '.short_link-save' )

		var popupOptions = { 'escClose': false, 'bgClose': false }
		UIkit.modal( '#short_link-popup' ).hide()

		if (shortLinkForm) {
			shortLinkForm.addEventListener(
				'submit',
				function (e) {
					e.preventDefault()
					var method             = 'POST'
					var url                = 'includes/ajax.php'
					var xhr                = new XMLHttpRequest()
					xhr.open( method, url, true )
					xhr.onreadystatechange = function () {
						if ( xhr.readyState == 4 && xhr.status == 200 ) {
							var data   = JSON.parse( xhr.response );
							var domain = site_params.site_url
							if ( shortLinkForm ) {
								saveShortLinkForm.querySelector( 'input[name="short_url_id"]' ).value = data.short_id
								saveShortLinkForm.querySelector( 'input[name="long_url"]' ).value     = data.long_url
								saveShortLinkForm.querySelector( '.short_url' ).value                 = domain + '/' + data.short_id
								UIkit.modal( '#short_link-popup', popupOptions ).show()
							}
							afterSendRequest( shortLinkForm )
						}
					}
					beforeSendRequest( shortLinkForm )
					var formData           = new FormData( shortLinkForm )
					formData.append( 'action_name', 'generate_short_url_id' )
					xhr.send( formData )
				}
			)

		}
		if ( saveShortLinkForm ) {
			shortLinkSaveBtn.onclick = function () {
				var method             = 'POST'
				var url                = 'includes/ajax.php'
				var xhr                = new XMLHttpRequest()
				xhr.open( method, url, true )
				xhr.onreadystatechange = function () {
					if ( xhr.readyState == 4 && xhr.status == 200 ) {
						var res = JSON.parse( xhr.response );
						if (res.success) {
							UIkit.modal( '#short_link-popup' ).hide()
							UIkit.notification(
								{
									message: 'Đã lưu thành công',
									status: 'success',
									pos: 'bottom-right',
									timeout: 3000
								}
							);
							addNewRow( res.data );
							saveLinkTitle();
						} else {
							UIkit.notification(
								{
									message: res.message,
									status: 'danger',
									pos: 'bottom-right',
									timeout: 3000
								}
							);
						}
					}
				}
				var formData           = new FormData( saveShortLinkForm )
				formData.append( 'action_name', 'save_short_url_id' )
				xhr.send( formData )
			}
			copyShortLinkBtn.addEventListener(
				'click',
				function () {
					copyCmd( saveShortLinkForm.querySelector( 'input[name="short_url"]' ) )
				}
			)
		}
	}
)