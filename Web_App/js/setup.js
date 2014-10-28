$(document).ready( function() {

getPopularBooks();

}); //end of document.ready()

var rootURL= "http://localhost:8888/api/index.php";

function getPopularBooks() {
	$.ajax({
		type: 'GET',
		url: rootURL + "/getPopularBooks",
		dataType: "json",
		success: function (data) {
			console.log(data);
			alert(JSON.stringify(data));	
	}	
	});
}