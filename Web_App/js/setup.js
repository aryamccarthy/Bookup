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

		var bookObjs = data.PopularBooks;


			for(var i=0; i<bookObjs.length; i++){
				var parsedBooks = $.parseJSON(bookObjs[i]);
				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors;
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
				console.log(thumbnail);
			}
	}	
	});
}