<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Search</h2>
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            <div class="mb-3">
                                <label for="name"><strong>Name</strong></label>
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="surname"><strong>Surname</strong></label>
                                <input type="text" class="form-control" name="surname" placeholder="Surname" required>
                            </div>
                            <div class="mb-3">
                                <label for="day"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><strong>Day</strong></label>
                                <select id="day" name="day" class="form-select" aria-label="Default select example"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected value="1">Lunedì</option>
                                    <option value="2">Martedì</option>
                                    <option value="3">Mercoledì</option>
                                    <option value="4">Giovedì</option>
                                    <option value="5">Venerdì</option>
                                    <option value="6">Sabato</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hour"><strong>Hour</strong></label>
                                <select id="hour" name="hour" class="form-select" aria-label="Default select example"
                                    class="bg-gray-50 border boder-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                                dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                                dark:focus:border-blue-500">
                                    <option selected value="1">Prima ora (8.00 - 9.00)</option>
                                    <option value="2">Seconda ora (9.00 - 9.55)</option>
                                    <option value="3">Terza ora (9.55 - 10.50)</option>
                                    <option value="4">Quarta ora (11.05 - 12.05)</option>
                                    <option value="5">Quinta ora (12.05 - 13.00)</option>
                                    <option value="6">Sesta ora (13.00 - 13.50)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div id="result"></div>
    </div>


    <script>
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch('../server/user/teacher/search', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    // Display the result
                    let resultDiv = document.getElementById('result');
                    resultDiv.innerHTML = ''; // Clear previous results
                    if (data.length > 0) {
                        let div = `
                        <div class="container">
                            <p>
                                Teacher: ${data[0].name} ${data[0].surname}<br>
                                Day: ${data[0].day}<br>
                                Hour: ${data[0].hour}<br>
                                Class: ${data[0].class}<br>
                                Room: ${data[0].room}<br>
                            </p>
                        </div>`;
                        resultDiv.innerHTML += div;
                    } else {
                        resultDiv.textContent = `Teacher ${data[0].name} ${data[0].surname} hasn\'t got lesson in day: ${data[0].day} and hour:  ${data[0].hour}.`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>