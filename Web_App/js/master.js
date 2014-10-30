$(document).ready( function() {

	//bookObjTest();
	getBooks("getPopularBooks");
	getBooks("getRandomBook");

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
			console.log(data);
			var bookObjs = data.Books; 
			for(var i=0; i<bookObjs.length; i+=2){	
				var parsedBooks = $.parseJSON(bookObjs[i]);	
				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors;
				var description =parsedBooks.items[0].volumeInfo.description;
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
				var cover = new Image();
				cover.src = thumbnail;
				var newBook= new Book(title, author, cover,description);
			 	if(sourceURL=="getPopularBooks"){
			 		generateHTMLForSetupPage(newBook);
			 	}
			 	else if(sourceURL=="getRandomBook"){
			 		generateHTMLForDiscoveryPage(newBook);
			 	}
			 	else if(sourceURL=="getReadingList"){
			 		generateHTMLForReadingList(newBook);
			 	}
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

function generateHTMLForSetupPage(Book){
	var account_section = document.getElementById("book_covers_to_rate");
	var bookItem = document.createElement("li");
	var title = document.createElement("p")
	title.innerHTML=Book.title;
	var author = document.createElement("p");
	author.innerHTML=Book.author;

	bookItem.appendChild(title);
	bookItem.appendChild(author);
	bookItem.appendChild(Book.cover);

	account_section.appendChild(bookItem);

}


function generateHTMLForDiscoveryPage(Book){
	$("#book_title").html(Book.title);
	$("#book_author").html(Book.author);
	$("#book_description").html(Book.description);
	$("#book_cover").attr("src", Book.cover.src);
}

function generateHTMLForReadingList(Book){

}
