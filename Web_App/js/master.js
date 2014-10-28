$(document).ready( function() {

	getRandomBook();

	for( var i=0; i<discoveryBooks.length; i++){
		//TODO: generate html for each book in setup array
	}

}); //end of document.ready()

var rootURL= "http://localhost:8888/api/index.php";

var discoveryBooks = [];

//untested, waiting on completed api method
function getRandomBook() {

	$.ajax({
		type: 'GET',
		url: rootURL + "/getRandomBook",
		dataType: "json",
		success: function (data) {
			var bookObjs = data.PopularBooks; //will be something other than PopularBooks

			for(var i=0; i<bookObjs.length; i++){
				var parsedBooks = $.parseJSON(bookObjs[i]);	

				//get info from return json object
				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors;
				var description =parsedBooks.items[0].volumeInfo.description;
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;

				var cover = new Image();
				cover.src = thumbnail.imgPath;
				var newBook= new discoveryBooks(title, author, description, cover);
			 	discoveryBooks.push(newBook);
			}
		}	
	});
}


function Book( title, author, description, cover){
	this. title=title;
	this.author=author;
	this.description=description;
	this.thumbnail=thumbnail;
}