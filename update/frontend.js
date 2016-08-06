$( document ).ready( function() {
	var _errorMsg = function( msg ) {
		console.log( msg );
		alert( msg );
	}

	var modifyView = function( data ) {
		$( '#stepElement' + data.activeStep ).addClass( 'active' );
		$( '#stepElement' + ( data.activeStep - 1 ) ).removeClass( 'active' );

		$( '#contentContainer' ).html( data.contentHTML );
	}

	var sendRequest = function() {
		console.log( 'sendRequest called' );
	
		$.get( "./index.php?action=backend", function( dataStr ) {
			try {
				var data = JSON.parse( dataStr );
			} catch (e) {
				_errorMsg( 'Error (json parse): ' + dataStr );
				return;
			}

			console.log( 'got: ' + dataStr );

			//modify view
			modifyView( data );

			if ( data.error ) {
				_errorMsg( 'Error: ' + data.error );
				return;
			}

			if ( data.converterDone ) return;

			//do new request...
			sendRequest();
		} ).fail( function( data ) {
			if ( data.readyState === 0 && data.status === 0 && data.statusText === "error" ) return;

			_errorMsg( 'Error (fail): ' + JSON.stringify( data ) );

			//try do new request...
			//sendRequest();
		} );
	}

	$( '#startButton' ).click( sendRequest );
	//sendRequest();
} );