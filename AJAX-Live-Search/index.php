<!DOCTYPE html>
<html>
<head>
	<title>Live Search</title>
	<link rel="stylesheet" href="styles.css">	

</head>
<body>

	<input type="text" name="search" id="search" placeholder="Search...">
	<div id="output"></div>

	<script type="text/javascript">
                var searchBar = document.getElementById('search'),
                outputContainer = document.getElementById('output')
                var ajax = null;
                // initialize the number of items shown in the output
                var loadItem = 0; 
                searchBar.onkeyup = function () {
                var searchValue = searchBar.value;
                // remove spaces 
                searchValue = searchValue.replace(/^\s|\s+$/, "");
                if (searchValue !== "") {	
                    searchForData(searchValue);
                } else {
                    // clear the result content
                    clearOutput();
                }
            }
                function searchForData(value, isLoadMoreMode) {
                    // abort if ajax request is already there
                    // if (ajax && typeof ajax.abort === 'function') {
                    //     ajax.abort();
                    // }
                    // nocleaning result is set to true on load more mode
                    if (isLoadMoreMode !== true) {
                        clearOutput();
                    }
                    // create the ajax object
                    ajax = new XMLHttpRequest();
                    // the function to execute on ready state is changed
                    ajax.onreadystatechange = function () {
                        if (this.readyState === 4 && this.status === 200) {
                            try {
                                var json = JSON.parse(this.responseText)
                            } catch (e) {
                                notFound();
                                return;
                            }

                            if (json.length === 0) {
                                if (isLoadMoreMode) {
                                    alert('No more to load');
                                } else {
                                    notFound();
                                }
                            } else {
                                displayProduct(json);
                            }
                        }
                    }
                    // ajax connection and send request
                    ajax.open('GET', 'search.php?search=' + value + '&start=' + loadItem, true);
                    ajax.send();
                }
                function displayProduct(data) {
                    function createRow(rowData) {
                        var wrap = document.createElement("div");
                        // add a class name
                        wrap.className = 'items'
                        // product title
                        var title = document.createElement("div");
                        title.innerHTML = rowData.product_title;
                        // product image
                        var picture = document.createElement("div");
                        picture.innerHTML = rowData.product_image;
                        //product decription
                        var desc = document.createElement("div");
                        desc.innerHTML = rowData.product_desc;
                        // echo "<table>";
                        // $title   = $row['product_title'];
                        // $image = $row['product_image'];
                        // $description = $row['product_desc'];
                        // echo "<tr><td>".$title."</td><td>".$image."</td><td>".$description."</td></tr>";
                                
                        // echo "</table>";

                        //append the prodct's info to display as a wrap
                        wrap.appendChild(title);
						wrap.appendChild(picture);
                        wrap.appendChild(desc);

                        // the full output
                        outputContainer.appendChild(wrap);
                    }
                    // loop through the data
                    for (var i = 0, len = data.length; i < len; i++) {
                        var items = data[i];
                        createRow(items);
                    }
                    // append loadMoreButton to result container
                    // outputContainer.appendChild(loadMoreButton);
                    // increase the user count
                    loadItem += len;
                }
                function clearOutput() {
                    outputContainer.innerHTML = "";
                    loadItem = 0;
                }

                function notFound() {
                    outputContainer.innerHTML = "Product not Found";
                }

            </script>

</body>
</html>