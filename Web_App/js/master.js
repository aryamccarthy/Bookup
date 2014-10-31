$(document).ready( function() {
	// Add background
	$.backstretch('img/background.jpg');

	if(setupLoaded===true){
		getBooks("getPopularBooks");
	}
	else if(discoveryLoaded===true){
		getBooks("getRandomBook");
	}
	else if(listLoaded===true){
		getBooks("getReadingList?email=drizzuto@bookup.com");
	}

}); 

function greyOutElement (event) {
	var target = $(event.target);
	console.log(target);
	target.closest("li").fadeTo('fast',0.3).css('pointer-events','none').css('')
}

var setupLoaded = false;
var loginLoaded = false;
var discoveryLoaded = false;
var listLoaded = false;

var rootURL= "http://localhost:8888/api/index.php";

function checkForSetup(){
	setupLoaded=true;
}
function checkForLogin(){
	loginLoaded=true;
}
function checkForDiscovery(){
	discoveryLoaded=true;
}
function checkForList(){
	listLoaded=true;
}

var userEmail= "";

function setEmail(){
	var emailElem = document.getElementById("email");
	var emailVal=emailElem.getAttribute("value");
	console.log(emailVal);
	userEmail=emailVal;
}

function Book( title, author, cover, description){
	this.title=title;
	this.author=author;
	this.description=description;
	this.cover=cover;
}

//use for api calls to getRandomBook, getPopularBooks, getReadingList
function getBooks(sourceURL) {

	$.ajax({
		type: 'GET',
		url: rootURL + "/" + sourceURL,
		dataType: "json",
		success: function (data) {
			var bookObjs = data.Books; 
		 	if(sourceURL.indexOf("getReadingList") > -1){
		 		for(var i=0; i<bookObjs.length; i+=2){	
					var parsedBooks = $.parseJSON(bookObjs[i].book);	
					var title= parsedBooks.items[0].volumeInfo.title;
					var author =parsedBooks.items[0].volumeInfo.authors.join(', ');
					var description =parsedBooks.items[0].volumeInfo.description;
					var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
					var cover = new Image();
					cover.src = thumbnail;
					var newBook= new Book(title, author, cover,description);
				 	generateHTMLForReadingList(newBook);
				} 	
			 
			 }
			else if (sourceURL==="getRandomBook") {
				for(var i=0; i<bookObjs.length; i+=2){	
					var parsedBooks = $.parseJSON(bookObjs[i]);
					console.log(i);	
					console.log(bookObjs[i]);
					var title= parsedBooks.items[0].volumeInfo.title;
					var author =parsedBooks.items[0].volumeInfo.authors.join(', ');
					var description =parsedBooks.items[0].volumeInfo.description;
					var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
					var cover = new Image();
					cover.src = thumbnail;
					var newBook= new Book(title, author, cover,description);
				 	generateHTMLForDiscoveryPage(newBook);
				 	
				}
			}
			else if (sourceURL==="getPopularBooks") {
				for(var i=0; i<bookObjs.length; i+=1){	
					var parsedBooks = $.parseJSON(bookObjs[i]);
					console.log(i);
					if (i === 4) continue; // DELETE THIS LINE WHEN DB IS CLEANED UP
					console.log(parsedBooks.items[0].volumeInfo.industryIdentifiers);
					var title= parsedBooks.items[0].volumeInfo.title;
					var author =parsedBooks.items[0].volumeInfo.authors.join(', ');
					var description =parsedBooks.items[0].volumeInfo.description;
					var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
					var cover = new Image();
					cover.src = thumbnail;
					var newBook= new Book(title, author, cover,description);
				 		generateHTMLForSetupPage(newBook);
				 	
				}
				$('.setupratingbutton').click(greyOutElement);
			}
		}	
	});
}


//TODO: implement and test
function addBookToReadingList(email, isbn) {

	$.ajax({
		type: 'POST',
		url: rootURL + "/addBookToReadingList?email=" + email + "&isbn=" + isbn,
		dataType: " ",
		success: function (data) {
			
		}	
	});
}

//TODO: implement and test
function submitBookFeedback(email, rating, isbn) {

	$.ajax({
		type: 'POST',
		url: rootURL + "/submitBookFeedback?email=" + email + "&rating=" + rating + "&isbn=" +isbn, 
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


function generateHTMLForSetupPage(Book){
	var account_section = document.getElementById("book_covers_to_rate");
	var bookItem = document.createElement("li");
	var title = document.createElement("p")
	title.innerHTML=Book.title;
	var author = document.createElement("p");
	author.innerHTML=Book.author;

	//TODO: wire up submitBookFeedback method here
	var likeButton= document.createElement("button");
	likeButton.setAttribute("value", "1");
	likeButton.setAttribute("class", "twobutton setupratingbutton");
	likeButton.innerHTML="<img src='img/like.png' alt='Like' />";
	var dislikeButton= document.createElement("button");
	dislikeButton.setAttribute("value", "-1");
	dislikeButton.setAttribute("class", "twobutton setupratingbutton");
	dislikeButton.innerHTML="<img src='img/dislike.png' alt='Dislike' />";

	var buttonDiv = document.createElement('div');

	bookItem.appendChild(title);
	bookItem.appendChild(author);
	bookItem.appendChild(Book.cover);
	//bookItem.appendChild(document.createElement('br'));
	buttonDiv.appendChild(dislikeButton);
	buttonDiv.appendChild(likeButton);
	bookItem.appendChild(buttonDiv);
	account_section.appendChild(bookItem);

}


function generateHTMLForDiscoveryPage(Book){
	$("#book_title").html(Book.title);
	$("#book_author").html(Book.author);
	$("#book_description").html(Book.description);
	$("#book_cover").attr("src", Book.cover.src);
}

function generateHTMLForReadingList(Book){
	console.log("ok");
	$("#list_title").html(Book.title);
	$("#list_author").html(Book.author);
	$("#list_description").html(Book.description);
	$("#list_cover").attr("src", Book.cover.src);
	var listing=document.createElement("li");
	listing.innerHTML=Book.title;
	var sidebar_list=document.getElementById("list_books");
	sidebar_list.appendChild(listing);


}
