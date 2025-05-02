<div class="container">
    <h1>Preferences</h1>
    <!-- Dropdown for selecting the type of travel -->
    <div class="form-group">
        <label for="type">Type</label>
        <select id="type">
            <option value="sightseeing">Sightseeing</option>
        </select>
    </div>

    <!-- Dropdown for selecting the country -->
    <div class="form-group">
        <label for="where">Where</label>
        <select id="where">
            <option value="" disabled selected>Select a country</option>
        </select>
    </div>

    <!-- Input field for selecting the date -->
    <div class="form-group">
        <label for="when">When</label>
        <input id="datepicker" placeholder="Select Date">
    </div>

    <!-- Input field for specifying the number of travelers -->
    <div class="form-group">
        <label for="who">Who</label>
        <input type="text" id="who" placeholder="2 Pers. 1 Child.">
    </div>

    <!-- Budget slider -->
    <div class="form-group">
        <label>Options:</label>
        <div class="options">
            <label for="budget">Budget</label>
            <div class="slider-container">
                <span id="budgetValue">0€</span>
                <input type="range" id="budget" min="1500" max="10000" step="100" oninput="updateBudgetValue()">
            </div>
            <span>1500€ - 10.000€+</span>
        </div>
    </div>

    <!-- Filters for stay and travel options -->
    <div class="filters">
        <div>
            <label>Stay Filters:</label>
            <label><input type="checkbox"> 4+ stars</label>
            <label><input type="checkbox"> Hotels</label>
            <label><input type="checkbox"> Airbnb</label>
        </div>
        <div>
            <label>Travel Filters:</label>
            <label><input type="checkbox"> Public Transport</label>
            <label><input type="checkbox"> Taxi</label>
            <label><input type="checkbox"> Car</label>
        </div>
    </div>

    <!-- Navigation buttons -->
    <div class="buttons">
        <button>Back</button>
        <button>Next</button>
    </div>
</div>

<script>
    // Function to parse the CSV file and populate the country dropdown
    async function parseCSV() {
        try {
            const response = await fetch('./include/pages/countries.csv');
            const response = await fetch('include/pages/countries.csv');
            console.log(response)
            const csvText = await response.text();
            const rows = csvText.split('\n').slice(1); // Skip the header row

            const countries = rows
                .map(row => {
                    const [longitude, latitude, country] = row.split(',');
                    // Validate the row structure
                    if (!longitude || !latitude || !country) {
                        console.warn('Invalid row skipped:', row);
                        return null; // Skip invalid rows
                    }
                    return {
                        longitude: parseFloat(longitude),
                        latitude: parseFloat(latitude),
                        country: country.trim()
                    };
                })
                .filter(country => country !== null); // Remove invalid rows

            populateCountrySelect(countries);
        } catch (error) {
            console.error('Error parsing CSV file:', error);
        }
    }

    // Function to populate the country dropdown with options
    function populateCountrySelect(countries) {
        const countrySelect = document.getElementById('where');
        countries.forEach(country => {
            const option = document.createElement('option');
            // Include the country name in the value along with longitude and latitude
            option.value = JSON.stringify({
                longitude: country.longitude,
                latitude: country.latitude,
                country: country.country
            });
            option.textContent = country.country;
            countrySelect.appendChild(option);
        });

        // Add an event listener to update the weather data when a country is selected
        countrySelect.addEventListener('change', function () {
            const selectedCountry = JSON.parse(this.value);
            const year = new Date().getFullYear();
            const month = new Date().getMonth() + 1; // Months are 0-indexed
            updateWeatherData(year, month, selectedCountry);
        });
    }

    // Function to fetch weather data for a specific month and country
    async function fetchWeatherDataForMonth(year, month, country) {
        const startDate = `${year - 1}-${month.toString().padStart(2, '0')}-01`;
        const endDate = `${year - 1}-${month.toString().padStart(2, '0')}-${new Date(year - 1, month, 0).getDate()}`; // Correct last day of the month
        const url = `https://meteostat.p.rapidapi.com/point/daily?lat=${country.latitude}&lon=${country.longitude}&start=${startDate}&end=${endDate}`;

        try {
            const weatherResponse = await fetch(url, {
                method: 'GET',
                headers: {
                    'x-rapidapi-host': 'meteostat.p.rapidapi.com',
                    'x-rapidapi-key': '7fb5cc25ccmsh7ff22ca39fa4f6ep14c3eejsned96868d3b3c' // Replace with your RapidAPI key
                }
            });

            if (!weatherResponse.ok) {
                throw new Error(`HTTP error! Status: ${weatherResponse.status}`);
            }

            const weatherData = await weatherResponse.json();

            if (weatherData && weatherData.data) {
                //console.log(weatherData.data);
                // Process the weather data to extract relevant information
                window.weatherData = weatherData.data.reduce((acc, day) => {
                    const formattedDate = addOneYear(day.date); // Add 1 year to the date
                    const conditions = [];

                    if (day.prcp > 0) conditions.push("🌧️");
                    else if (day.snow > 0) conditions.push("❄️");
                    else if (day.wspd > 30) conditions.push("💨");
                    else if (day.tsun > 5 * 60) conditions.push("☀️");
                    else if ('tsun' in day) conditions.push("☁️");

                    const result = conditions.length > 0 ? conditions.join(", ") : "N/A";
                    acc[formattedDate] = {
                        temp: `${day.tavg}°C`,
                        emoji: `${result}`
                    };
                    return acc;
                }, {});
                return window.weatherData;
            }
        } catch (error) {
            console.error('Error fetching weather data:', error);
        }
        return {};
    }

    function addOneYear(formattedDate) {
        const date = new Date(formattedDate);
        date.setFullYear(date.getFullYear() + 1);

        // Reformat to YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    // Function to update weather data and refresh the datepicker
    async function updateWeatherData(year, month, country) {
        //console.log(`Year: ${year}, Month: ${month}`);
        const weatherData = await fetchWeatherDataForMonth(year, month, country);
        window.weatherData = weatherData;
        addCustomInformation(year, month);
    }

    // Function to add custom weather information to the datepicker
    function addCustomInformation(year, month) {
        setTimeout(function () {
            $(".ui-datepicker-calendar td").each(function () {
                let date = $(this).text();
                if (/\d/.test(date)) {
                    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${date.padStart(2, '0')}`;
                    if (window.weatherData && window.weatherData[formattedDate]) {
                        const weather = window.weatherData[formattedDate];
                        const anchor = $(this).find("a");
                        anchor.attr('data-custom', `${weather.temp} ${weather.emoji}`);
                    }
                }
            });
        }, 0);
    }

    // Initialize the datepicker and parse the CSV file on page load
    $(function () {
        $("#datepicker").datepicker({
            firstDay: 1, // Set Monday as the first day of the week
            beforeShow: function (input, inst) {
                const year = inst.drawYear || new Date().getFullYear(); // Fallback to current year
                const month = (inst.drawMonth || new Date().getMonth()) + 1; // Fallback to current month
                const countrySelect = document.getElementById('where');
                const selectedCountry = JSON.parse(countrySelect.value);
                updateWeatherData(year, month, selectedCountry);
            },
            onChangeMonthYear: function (year, month) {
                const countrySelect = document.getElementById('where');
                const selectedCountry = JSON.parse(countrySelect.value);
                updateWeatherData(year, month, selectedCountry);
            },
            beforeShowDay: function (date) {
                return [true, date.getDay() === 6 || date.getDay() === 0 ? "weekend" : "weekday"];
            }
        });

        parseCSV();
    });

    // Function to update the budget slider value
    function updateBudgetValue() {
        let budget = document.getElementById("budget");
        let budgetValue = document.getElementById("budgetValue");
        let value = budget.value;
        budgetValue.textContent = value + "€";
        let percent = ((value - budget.min) / (budget.max - budget.min)) * 100;
        budgetValue.style.left = `calc(${percent}% + (${8 - percent * 0.15}px))`;
    }

    // Initialize the budget value on page load
    document.addEventListener("DOMContentLoaded", function () {
        updateBudgetValue();
    });
</script>

<style>
    /* Highlight weekends in the datepicker */
    .ui-datepicker .weekend .ui-state-default {
        background: #FEA;
    }
</style>