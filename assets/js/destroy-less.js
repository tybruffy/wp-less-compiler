function destroyLessCache( pathToCss ) { // e.g. '/css/' or '/stylesheets/'
	if (!window.localStorage ) {
		return;
	}
	var host = window.location.host;
	var protocol = window.location.protocol;
	var keyPrefix = pathToCss;
	for ( var key in window.localStorage ) {
		if ( key.indexOf( keyPrefix ) === 0 ) {
			delete window.localStorage[ key ];
		}
	}
}

destroyLessCache( less_url );