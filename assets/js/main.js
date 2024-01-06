var copyCmd = function (copyTextEl) {
	copyTextEl.select()
	document.execCommand( "copy" );
	UIkit.notification(
		{
			message: 'Đã sao chép',
			status: 'success',
			pos: 'bottom-right',
			timeout: 2000
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function (event) {
		var copyLinkTableList = document.querySelectorAll( '.table-list .short_link-copy' )

		if ( copyLinkTableList.length > 0 ) {
			copyLinkTableList.forEach(
				function ( copyLinkTableListBtn ) {
					copyLinkTableListBtn.onclick = function () {
							copyCmd( copyLinkTableListBtn.closest( 'td' ).querySelector( 'input.short_url' ) )
					}
				}
			)
		}
	}
)