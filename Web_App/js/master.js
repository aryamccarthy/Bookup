$(document).ready( function() {
	$.backstretch('img/background2.jpg');

 	 userEmail=$("#userinfo").attr("data-email");

	if(setupLoaded===true){
		getPopularBooks();
	}
	else if(discoveryLoaded===true){
		checkForNewUser();
		var setButton=document.getElementById("next");
		setButton.setAttribute("onclick", "getRecommendedBook()")
		getRecommendedBook();
	}
	else if(listLoaded===true){
		getReadingList();
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

function getRecommendedBook(){
	$.ajax({
		type: 'GET',
		url: rootURL + "/getRecommendedBook/" + userEmail,
		dataType: "json",
		success: function (data) {
			console.log(data);
			var bookObjs = data.Books; 
			 for(var i=0; i<bookObjs.length; i++){	
			 	var title= bookObjs[i].title;
			 	var author =bookObjs[i].author.join(', ');			
			 	var description =bookObjs[i].description;
			 	var thumbnail=bookObjs[i].thumbnail;
				var isbn=bookObjs[i].isbn;
			}
			var cover = new Image();
			cover.src = thumbnail;
			var newBook= new Book(title, author, cover,description,isbn);
			generateHTMLForDiscoveryPage(newBook);		 	
		}
	});
}

function getPopularBooks(){
	$.ajax({
		type: 'GET',
		url: rootURL + "/getPopularBooks",
		dataType: "json",
		success: function (data) {
			console.log(data);
			var bookObjs = data.Books; 
			 for(var i=0; i<bookObjs.length; i++){	
			 	var title= bookObjs[i].title;
			 	var author =bookObjs[i].author.join(', ');			
			 	var description =bookObjs[i].description;
			 	var thumbnail=bookObjs[i].thumbnail;
				var isbn=bookObjs[i].isbn;
				var cover = new Image();
				cover.src = thumbnail;
				var newBook= new Book(title, author, cover,description,isbn);				 	
				generateHTMLForSetupPage(newBook);
			}	 	
		
			$('.setupratingbutton').click(greyOutElement);
			$('.setupratingbutton').click(getUserDataAndSubmit);
		}
	});
}


function getReadingList ()	{

	$.ajax({
		type: 'GET',
		url: rootURL + "/getReadingList/"+userEmail,
		dataType: "json",
		success: function (data) {
			var bookObjs = data.Books; 
			console.log(data);
	 		if (bookObjs.length === 0) {
	 			$('#this_book button').remove();
	 			$('#this_book article').css('text-align', 'center');
	 			$('#this_book h2').css('font-weight', 'normal').css('margin-top', '170px').css({'position':'relative', 'left': '-100px'}).text("You have nothing in your reading list.");
	 			$('#list_books').append($('<img>').attr('src', 'img/generic_book.jpg').css('height', '200px').css('cursor', 'pointer'));
	 		}
	 		for(var i=0; i<bookObjs.length; i++){	
				var title= bookObjs[i].title;
			 	var author =bookObjs[i].author.join(', ');			
			 	var description =bookObjs[i].description;
			 	var thumbnail=bookObjs[i].thumbnail;
				var isbn=bookObjs[i].isbn;
				var cover = new Image();
				cover.src = thumbnail;
				var newBook= new Book(title, author, cover,description,isbn);	
			 	listBooks.push(newBook);
			 	generateHTMLForReadingList(newBook, i);
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
			console.log(userEmail);
			console.log(isbn);
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
			console.log(userEmail);
			console.log(isbn);
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
			//sweetAlert("Response", JSON.stringify(data), "info");
		}	
	});
}


function overlay(id) {
   var el = document.getElementById(id);
   el.style.visibility = (el.style.visibility === "visible") ? "hidden" : "visible";
 //  if (discoveryLoaded==true)
   	//	$( "#discovery_section" ).css({ opacity: 0.7});
   //	else if (listLoaded==true)
   	//	$( "main" ).css({ opacity: 0.7});

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

function dealWithRatingandDeletingFromMainSection(isbn){
	overlay('delete_and_rate_from_list');
	removeBookFromReadingList(isbn);

}

function showReadingListBook(selectedTitle){
	 for (var i=0; i<listBooks.length; i++){
		if(listBooks[i].title == selectedTitle){
			$("#list_title").html(listBooks[i].title);
			$("#list_author").html(listBooks[i].author);
			$("#list_description").html(listBooks[i].description);
			$("#list_cover").attr("src", listBooks[i].cover.src);
		  	var listButtons= document.getElementsByClassName("twobutton");
		  	var index = document.getElementById("isbn"+i);
			var isbn = index.innerHTML;
			for(var j=0; j<3; j++){
				listButtons[j].setAttribute("onclick","dealWithRatingandDeletingFromMainSection("+isbn+")" );
			}
	  	}
		
	 }
}
