document.getElementById('search').addEventListener('click', function () {
    const searchInput = document.getElementById('book-search');
    const resultsList = document.getElementById('search-results');
    const query = searchInput.value.trim();

    // Clear previous results
    resultsList.innerHTML = '';

    // Check if query is empty
    if (query === '') {
        resultsList.innerHTML = '<li>Please enter a book title to search.</li>';
        return;
    }

    // Send request to searchBooks.php
    fetch(`searchBooks.php?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            // Display results
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(book => {
                    const li = document.createElement('li');
                    li.textContent = book.titre; // Use the book title
                    li.addEventListener('click', () => {
                        // Open a new window with both the book id and title
                        window.open(`viewBookDetails.php?id=${encodeURIComponent(book.id)}&title=${encodeURIComponent(book.titre)}`, 
                                    'BookDetailsWindow', 'width=600,height=400');
                        resultsList.innerHTML = ''; // Clear results list
                    });
                    resultsList.appendChild(li);
                });
            } else {
                resultsList.innerHTML = '<li>No results found.</li>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultsList.innerHTML = '<li>Error searching for books. Please try again later.</li>';
        });
});
