Logger = function() { }

Logger.AlertError = true;
Logger.LogToConsole = true;
Logger.LogToService = false;
Logger.LogServiceAddr = "http://192.168.1.10/logger.service/logger.php";

Logger.HandleError = function(message) {
	
	if (Logger.AlertError)
		alert(message);
	
	if (Logger.LogToConsole)
	{
		if (typeof window.console != 'undefined')
			console.log(message);
	}
	
}

Logger.LogMessage = function(message) {
	
	if (Logger.LogToConsole)
	{
		if (typeof window.console != 'undefined')
			console.log(message);
	}
	
	if (Logger.LogToService)
	{
		$.ajax({
			type: "POST",
			data: "message="+escape(message),
			url: Logger.LogServiceAddr,
			success: function(response)	{
				
			},
			error: function(xhr, options, error) {
				//alert(xhr.responseText);
			}
		});
	}
	
}