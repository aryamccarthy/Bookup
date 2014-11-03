$(document).ready( function() {
	// Add background
	$.backstretch('img/background2.jpg');

 	 userEmail=$("#userinfo").attr("data-email");

	if(setupLoaded===true){
		getBooks("getPopularBooks");
	}
	else if(discoveryLoaded===true){
		checkForNewUser();
		getBooks("getRandomBook");
	}
	else if(listLoaded===true){
		getBooks("getReadingList?email="+userEmail);
	}

}); 

function greyOutElement (event) {
	var target = $(event.target);
	target.closest("li").fadeTo('fast',0.3).css('pointer-events','none').css('')
}
var listBooks = [];
var setupLoaded = false;
var discoveryLoaded = false;
var listLoaded = false;

var rootURL= "http://localhost:8888/api/index.php";

function checkForNewUser(){
	$.ajax({
		type: 'GET',
		url: rootURL + "/isNewUser?email="+userEmail,
		dataType: "json",
		success: function (data) {
			if( data.newUser===true){
				window.location.href="setup.php";
			}
		}
	});
}

function checkForSetup(){
	setupLoaded=true;
}
function checkForDiscovery(){
	discoveryLoaded=true;
}
function checkForList(){
	listLoaded=true;
}

var userEmail= "";

function Book( title, author, cover, description, isbn){
	this.title=title;
	this.author=author;
	this.description=description;
	this.cover=cover;
	this.isbn=isbn;
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
		 		if (bookObjs.length === 0) {
		 			$('#this_book button').remove();
		 			$('#this_book article').css('text-align', 'center').append($('<h2>'));
		 			$('#this_book h2').css('font-weight', 'normal').text("You have nothing in your reading list.");
		 			$('#list_books').append($('<img>').attr('src', 'img/generic_book.jpg').css('height', '200px').css('cursor', 'pointer'));
		 		}
		 		for(var i=0; i<bookObjs.length; i+=2){	
					var parsedBooks = $.parseJSON(bookObjs[i]);
					var title= parsedBooks.items[0].volumeInfo.title;
					var author =parsedBooks.items[0].volumeInfo.authors.join(', ');
					var description =parsedBooks.items[0].volumeInfo.description;
					var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
					for (var j=0; j<parsedBooks.items[0].volumeInfo.industryIdentifiers.length; j++){
						if (parsedBooks.items[0].volumeInfo.industryIdentifiers[j].type==="ISBN_13"){
							var isbn=parsedBooks.items[0].volumeInfo.industryIdentifiers[j].identifier;
						}
					}
					var cover = new Image();
					cover.src = thumbnail;
					var newBook= new Book(title, author, cover,description,isbn);
				 	generateHTMLForReadingList(newBook, i);
				 	listBooks.push(newBook);
				} 	
			 
			 }
			else if (sourceURL==="getRandomBook") {
				for(var i=0; i<bookObjs.length; i+=2){	
					var parsedBooks = $.parseJSON(bookObjs[i]);
					var title= parsedBooks.items[0].volumeInfo.title;
					var author =parsedBooks.items[0].volumeInfo.authors.join(', ');
					var description =parsedBooks.items[0].volumeInfo.description;
					var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
					for (var j=0; j<parsedBooks.items[0].volumeInfo.industryIdentifiers.length; j++){
						if (parsedBooks.items[0].volumeInfo.industryIdentifiers[j].type==="ISBN_13"){
							var isbn=parsedBooks.items[0].volumeInfo.industryIdentifiers[j].identifier;
						}
					}
					var cover = new Image();
					cover.src = thumbnail;
					var newBook= new Book(title, author, cover,description,isbn);
				 	generateHTMLForDiscoveryPage(newBook);
				 	
				}
			}
			else if (sourceURL==="getPopularBooks") {
				for(var i=0; i<bookObjs.length; i+=1){	
					console.log(parsedBooks);
					var parsedBooks = $.parseJSON(bookObjs[i]);
					var title= parsedBooks.items[0].volumeInfo.title;
					var author =(parsedBooks.items[0].volumeInfo.authors || []).join(', ');
					var description =parsedBooks.items[0].volumeInfo.description;
					if (parsedBooks.items[0].volumeInfo.imageLinks) {
						var thumbnail=parsedBooks.items[0].volumeInfo.imageLinks.thumbnail;
					} else {
						var thumbnail = 'img/generic_book.jpg';
					}
					for (var j=0; j<parsedBooks.items[0].volumeInfo.industryIdentifiers.length; j++){
						if (parsedBooks.items[0].volumeInfo.industryIdentifiers[j].type==="ISBN_13"){
							var isbn=parsedBooks.items[0].volumeInfo.industryIdentifiers[j].identifier;
						}
					}
					var cover = new Image();
					cover.src = thumbnail;
					var newBook= new Book(title, author, cover,description, isbn);
				 		generateHTMLForSetupPage(newBook);
				 	
				}
				console.log($('.setupratingbutton'));
				$('.setupratingbutton').click(greyOutElement);
				$('.setupratingbutton').click(getUserDataAndSubmit);
			}
		}	
	});
}

function addBookToReadingList(isbn) {
	$.ajax({
		type: 'POST',
		url: rootURL + "/addBookToReadingList",
		dataType: "json",
		data: {email: userEmail, isbn: isbn},
		success: function (data) {
			console.log("book sucessfully added");
		}	
	});
}
function removeBookFromReadingList(isbn) {
	$.ajax({
		type: 'POST',
		url: rootURL + "/removeBookFromReadingList",
		dataType: "json",
		data: {email: userEmail, isbn: isbn},
		success: function (data) {
			console.log("book sucessfully removed");
		}	
	});
}

function getUserDataAndSubmit (event) {
	var rating = $(event.target).closest('button').attr('value');
	var isbn = $(event.target).closest("li").find(".isbn").text();
	console.log(userEmail + '\n' + rating + '\n' + isbn);
	submitBookFeedback(rating, isbn);
}

//TODO: implement and test
function submitBookFeedback(rating, isbn) {

	$.ajax({
		type: 'POST',
		url: rootURL + "/submitBookFeedback",
		dataType: "json",
		data: {email: userEmail, rating: rating, isbn: isbn},
		success: function (data) {
			sweetAlert("Response", JSON.stringify(data), "info");
		}	
	});
}

// DEPRECATING
/*var previousBook = $( "#previous" );
$( previousBook ).click(function() {
	console.log("display previous book");
	//TODO: implementation 
});*/


function overlay(id) {
  var el = document.getElementById(id);
  el.style.visibility = (el.style.visibility === "visible") ? "hidden" : "visible";
}


function generateHTMLForSetupPage(Book){
	var account_section = document.getElementById("book_covers_to_rate");
	var bookItem = document.createElement("li");
	var title = document.createElement("p")
	title.innerHTML=Book.title;
	var author = document.createElement("p");
	author.innerHTML=Book.author;
	var isbn = document.createElement("p");
	isbn.innerHTML=Book.isbn;
	isbn.setAttribute("class", "isbn");
	isbn.style.display="none";

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
	bookItem.appendChild(isbn);
	bookItem.appendChild(Book.cover);
	buttonDiv.appendChild(likeButton);
	buttonDiv.appendChild(dislikeButton);
	bookItem.appendChild(buttonDiv);
	account_section.appendChild(bookItem);
}


function generateHTMLForDiscoveryPage(Book){
	$("#book_title").html(Book.title);
	$("#book_author").html(Book.author);
	$("#book_description").html(Book.description || "");
	$("#book_cover").attr("src", Book.cover.src);
	var addButton =document.getElementById("add to list");
	addButton.setAttribute("onclick", "addBookToReadingList("+Book.isbn+")");
}

function generateHTMLForReadingList(Book, index){
	$("#list_title").html(Book.title);
	$("#list_author").html(Book.author);
	$("#list_description").html(Book.description || "");
	$("#list_cover").attr("src", Book.cover.src);
	var listing=document.createElement("li");
	listing.setAttribute("title", Book.title);
	
	var isbn=document.createElement('p');
	isbn.setAttribute('id', 'isbn'+index);
	isbn.innerHTML=Book.isbn;
	isbn.style.display="none";

	var listNum=document.createElement('p');
	listNum.setAttribute('id', 'index'+Book.title);
	listNum.innerHTML=index;
	listNum.style.display="none";

	var delete_listing=document.createElement("p");
	listing.setAttribute("id", "list_item");
	delete_listing.setAttribute("id", "delete_x");
	delete_listing.innerHTML=" X";
	delete_listing.setAttribute("onclick", "dealWithRatingandDeleting("+index+")");
	listing.setAttribute("onclick", "showReadingListBook(this.title)")
	listing.innerHTML=Book.title;
	var sidebar_list=document.getElementById("list_books");
	sidebar_list.appendChild(listing);
	listing.appendChild(delete_listing);
	listing.appendChild(isbn);
}

function dealWithRatingandDeleting(index){
	overlay('delete_and_rate_from_list');
	var isbn=document.getElementById("isbn"+index);
	removeBookFromReadingList(isbn.innerHTML);

}

function showReadingListBook(selectedTitle){
	 for (var i=0; i<listBooks.length; i++){
		if(listBooks[i].title == selectedTitle){
			$("#list_title").html(listBooks[i].title);
			$("#list_author").html(listBooks[i].author);
			$("#list_description").html(listBooks[i].description);
			$("#list_cover").attr("src", listBooks[i].cover.src);
		  	var listButtons= document.getElementsByClassName("twobutton listratingbutton");
		  	var index= document.getElementById("index"+listBooks[i].title);

			for(var j=0; j<3; j++){
				listButtons[j].setAttribute("onclick","dealWithRatingandDeleting("+index.innerHTML+")" );
			}
	  	}
		
	 }
}
