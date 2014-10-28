$(document).ready( function() {

	getPopularBooks();

	for( var i=0; i<booksForSetup.length; i++){
		//TODO: generate html for each book in setup array
	}

}); //end of document.ready()

var rootURL= "http://localhost:8888/api/index.php";

var booksForSetup = [];

function getPopularBooks() {

	$.ajax({
		type: 'GET',
		url: rootURL + "/getPopularBooks",
		dataType: "json",
		success: function (data) {
			var bookObjs = data.PopularBooks;

			//creates a Book js object for each element in PopularBooks array
			//TODO: will probably need to adjust this since the format for the PopularBooks
			//array alternates between book data and isbn number 
			for(var i=0; i<bookObjs.length; i++){
				var parsedBooks = $.parseJSON(bookObjs[i]);				

				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors;
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
				
				console.log(thumbnail);

				var cover = new Image();
				cover.src = thumbnail.imgPath;
				var newBook= new Book(title, author, description, cover);
			 	booksForSetup.push(newBook);
			}
		}	
	});
}


function Book( title, author, cover){
	this. title=title;
	this.author=author;
	this.thumbnail=thumbnail;
}