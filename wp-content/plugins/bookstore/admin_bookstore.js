// alert("hello from the book store admin ")

const loadBooksByRestButton = document.getElementById( 'bookstore-load-books');
if(loadBooksByRestButton){
    loadBooksByRestButton.addEventListener('click' , function(){
        const allBooks = new wp.api.collections.Books();
        allBooks.fetch().done(
            function (books){
                const textarea = document.getElementById('bookstore-booklist');
                books.forEach(function (book){
                    textarea.value += book.title.rendered + ',' + book.link + ',\n';
                } )
            }
        )

    } );

}


/* using wordpress Featch api  */
const fetchBooksByRestButton = document.getElementById('bookstore-fetch-books');
if(fetchBooksByRestButton){
    fetchBooksByRestButton.addEventListener('click' , function(){
        // calling wpi - fetch 
        wp.apiFetch({path: '/wp/v2/books'}).then(
           (books) => {
             const textarea = document.getElementById( 'bookstore-booklist');
             books.map(
                (book)=>{
                    textarea.value += book.title.rendered + ',' + book.link + ',\n'
                }
             )
           } 
        )
      } );
}