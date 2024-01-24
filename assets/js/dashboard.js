var refresh_dashboard_data = function() {
    var allDailyViewsNumber = document.querySelector('.box__all-daily-views .card-number');
    var allWeeklyViewsBoxNumber = document.querySelector('.box__all-weekly-views .card-number');
    var allMonthlyViewsBoxNumber = document.querySelector('.box__all-monthly-views .card-number');
    var topCountryContent = document.querySelector('.section-top-country table tbody');
    var userListContent = document.querySelector('.users-list');

    var method             = 'POST'
    var url                = 'includes/ajax.php'
    var xhr                = new XMLHttpRequest()
    xhr.open( method, url, true )
    xhr.onreadystatechange = function () {
        if ( xhr.readyState == 4 && xhr.status == 200 ) {
            var res   = JSON.parse( xhr.response );
            allDailyViewsNumber.innerHTML = res.data.all_daily_views
            allWeeklyViewsBoxNumber.innerHTML = res.data.all_weekly_views
            allMonthlyViewsBoxNumber.innerHTML = res.data.all_monthly_views
            topCountryContent.innerHTML = res.data.country;
            userListContent.innerHTML = res.data.user_list;
        }
    }
    var formData = new FormData()
    formData.append( 'action_name', 'refresh_dashboard_data' )
    xhr.send( formData )
}

document.addEventListener(
	'DOMContentLoaded',
	function (event) {
        // var button = document.querySelector('#refresh_dashboard_data');
        // button.onclick = function() {
        //     refresh_dashboard_data();
        // }
        if (document.querySelector('body').classList.contains('page-dashboard')) {
            setInterval( refresh_dashboard_data, 30000)
        }
	}
)