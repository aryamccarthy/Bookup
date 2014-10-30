$(document).ready( function() {

	bookObjTest();

}); 

var rootURL= "http://localhost:8888/api/index.php";

function Book( title, author, cover, description){
	this. title=title;
	this.author=author;
	this.description=description;
	this.cover=cover;
}

//TODO: untested, waiting on completed api method
//use for api calls to getRandomBook, getPopularBooks, getReadingList
function getBooks(sourceURL) {
	$.ajax({
		type: 'GET',
		url: rootURL + "/" + sourceURL,
		dataType: "json",
		success: function (data) {
			var bookObjs = data.Books; 
			for(var i=0; i<bookObjs.length; i++){	
				var parsedBooks = $.parseJSON(bookObjs[i]);	
				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors;
				var description =parsedBooks.items[0].volumeInfo.description;
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
				var cover = new Image();
				cover.src = thumbnail.imgPath;
				var newBook= new Book(title, author, cover,description);
			 	
			 	generateHTML(newBook);
			}
		}	
	});
}


//TODO: implement and test
function addBookToReadingList() {

	$.ajax({
		type: 'POST',
		url: rootURL + "/addBookToReadingList",
		dataType: " ",
		success: function (data) {
			
		}	
	});
}


//TODO: implement and test
function submitBookFeedback() {

	$.ajax({
		type: 'POST',
		url: rootURL + "/submitBookFeedback",
		dataType: " ",
		success: function (data) {
			
		}	
	});
}


var previousBook = $( "#previous" );
$( previousBook ).click(function() {
	console.log("display previous book");
	//TODO: implementation 
});


function overlay() {
  var el = document.getElementById("rate_from_discovery");
  el.style.visibility = (el.style.visibility === "visible") ? "hidden" : "visible";
}


function bookObjTest() {

	$.ajax({
		type: 'GET',
		url: rootURL + "/getBookFromFirebase?isbn=9780001000391",
		dataType: "json",
		success: function (data) {
			//var bookObjs = data.PopularBooks;
			//for(var i=0; i<bookObjs.length; i++){
				var parsedBooks = $.parseJSON(data);

				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors[0];
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
				var description =parsedBooks.items[0].volumeInfo.description;
				var cover = new Image();
				cover.src = thumbnail;
				var newBook= new Book(title, author, cover, description);
			 	generateHTML(newBook);
		
		}	
	});
}

function generateHTML(Book){
	var account_section = document.getElementById("account_setup");

	account_section.appendChild(Book.cover);

	var title = document.createElement("figcaption");
	title.innerHTML=Book.title;
	account_section.appendChild(title);

	var author = document.createElement("figcaption");
	author.innerHTML=Book.author;
	account_section.appendChild(author);


}


