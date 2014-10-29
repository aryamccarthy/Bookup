$(document).ready( function() {

	getRandomBook();

	for( var i=0; i<discoveryBooks.length; i++){
		//TODO: generate html for each book in setup array
	}

}); 

var rootURL= "http://localhost:8888/api/index.php";

var discoveryBooks = [];
var readingList = [];

function Book( title, author, description, cover){
	this. title=title;
	this.author=author;
	this.description=description;
	this.thumbnail=thumbnail;
}

//TODO: untested, waiting on completed api method
function getRandomBook() {
	$.ajax({
		type: 'GET',
		url: rootURL + "/getRandomBook",
		dataType: "json",
		success: function (data) {
			var bookObjs = data.ReadingList; //will be something other than PopularBooks
			console.log(data);
			for(var i=0; i<bookObjs.length; i++){	
				var parsedBooks = $.parseJSON(bookObjs[i]);	

				//get info from return json object
				var title= parsedBooks.items[0].volumeInfo.title;
				var author =parsedBooks.items[0].volumeInfo.authors;
				var description =parsedBooks.items[0].volumeInfo.description;
				var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;

				var cover = new Image();
				cover.src = thumbnail.imgPath;
				
				var newBook= new Book(title, author, description, cover);
			 	discoveryBooks.push(newBook);
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
function getReadingList() {

	$.ajax({
		type: 'GET',
		url: rootURL + "/getReadingList",
		dataType: "json",
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

var nextBook = $( "#next" );
$( nextBook ).click(function() {
	console.log("display next book");
	//TODO: implementation 
});

function overlay() {
  var el = document.getElementById("rate_from_discovery");
  el.style.visibility = (el.style.visibility === "visible") ? "hidden" : "visible";
}
