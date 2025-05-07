<div class="container">
    <form method="get" id="travel-form">
        <!-- First row: Type and Where -->
        <div class="form-row">
            <!-- Dropdown for selecting the type of travel -->
            <div class="form-col">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="sightseeing">Sightseeing</option>
                        <option value="Beach">Beach</option>
                        <option value="Explore">Explore</option>
                        <option value="Ski">Ski</option>
                        <option value="City">City Break</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Relaxation">Relaxation</option>
                    </select>
                </div>
            </div>

            <!-- Dropdown for selecting the country -->
            <div class="form-col">
                <div class="form-group">
                    <label for="where">Where</label>
                    <select id="where" name="location">
                        <option value="" disabled selected>Select a country</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Second row: When and Who -->
        <div class="form-row">
            <!-- Input fields for selecting start and end dates -->
            <div class="form-col">
                <div class="form-group date-range-container">
                    <label>When</label>
                    <div class="date-inputs">
                        <div class="date-input">
                            <input type="text" id="startDate" name="startDate" placeholder="Check-in" readonly>
                        </div>
                        <div class="date-separator">→</div>
                        <div class="date-input">
                            <input type="text" id="endDate" name="endDate" placeholder="Check-out" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input field for specifying the number of travelers -->
            <div class="form-col">
                <label for="who">Who</label>
                <div class="dropdown-container">
                    <div class="dropdown-toggle" id="travelerToggle">3 Adults · 0 Children · 1 Room</div>
                    
                    <div class="dropdown-panel" id="travelerPanel">
                        <div class="counter-group">
                            <span class="counter-label">Adults</span>
                            <div class="counter">
                                <button type="button" onclick="updateCount('adults', -1)">−</button>
                                <span id="adultsCount">3</span>
                                <button type="button" onclick="updateCount('adults', 1)">+</button>
                            </div>
                        </div>

                        <div class="counter-group">
                            <span class="counter-label">Children</span>
                            <div class="counter">
                                <button type="button" onclick="updateCount('children', -1)">−</button>
                                <span id="childrenCount">0</span>
                                <button type="button" onclick="updateCount('children', 1)">+</button>
                            </div>
                        </div>

                        <div class="counter-group">
                            <span class="counter-label">Rooms</span>
                            <div class="counter">
                                <button type="button" onclick="updateCount('rooms', -1)">−</button>
                                <span id="roomsCount">1</span>
                                <button type="button" onclick="updateCount('rooms', 1)">+</button>
                            </div>
                        </div>

                        <button type="button" class="done-button" onclick="closeDropdown()">Done</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden input to store participants data -->
        <input type="hidden" id="participants" name="participants" value="">
        <!-- Hidden input to combine dates -->
        <input type="hidden" id="dates" name="dates" value="">
        
        <!-- Submit button to search for preferences -->
        <button type="submit" id="search-button" class="search-button" name="page" value="preferences">GO</button>
    </form>
</div>

<script>
    const toggle = document.getElementById('travelerToggle');
    const panel = document.getElementById('travelerPanel');
    const counts = {
        adults: 3,
        children: 0,
        rooms: 1
    };

    function updateDisplay() {
        document.getElementById('adultsCount').textContent = counts.adults;
        document.getElementById('childrenCount').textContent = counts.children;
        document.getElementById('roomsCount').textContent = counts.rooms;
        toggle.textContent = `${counts.adults} Adults · ${counts.children} Children · ${counts.rooms} Room${counts.rooms > 1 ? 's' : ''}`;
        
        // Update the hidden input with JSON data
        document.getElementById('participants').value = JSON.stringify(counts);
    }

    function updateCount(type, delta) {
        if (counts[type] + delta >= 0) {
            counts[type] += delta;
            updateDisplay();
        }
    }

    toggle.addEventListener('click', () => {
        panel.classList.toggle('active');
    });

    function closeDropdown() {
        panel.classList.remove('active');
    }

    // Initialize display and set the hidden input
    updateDisplay();

    // Update the form submission logic
    document.getElementById('travel-form').addEventListener('submit', function(e) {
        // Prevent the default form submission
        e.preventDefault();
        
        // Format the selected country data before submission
        const whereSelect = document.getElementById('where');
        
        // Check if a country is selected
        if (!whereSelect.value) {
            alert("Please select a country");
            return;
        }
        
        // Check if both dates are selected
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            alert("Please select both check-in and check-out dates");
            return;
        }
        
        // Combine dates for backward compatibility
        document.getElementById('dates').value = startDate + " - " + endDate;
        
        // Parse the country JSON and extract just the country name
        const countryData = JSON.parse(whereSelect.value);
        
        // Create the URL with correct parameters
        let url = 'index.php?';
        url += 'type=' + encodeURIComponent(document.getElementById('type').value);
        url += '&location=' + encodeURIComponent(countryData.country);
        url += '&dates=' + encodeURIComponent(document.getElementById('dates').value);
        url += '&startDate=' + encodeURIComponent(startDate);
        url += '&endDate=' + encodeURIComponent(endDate);
        url += '&participants=' + encodeURIComponent(document.getElementById('participants').value);
        url += '&page=preferences';
        
        // Navigate to the constructed URL
        window.location.href = url;
    });

    // Function to parse the CSV file and populate the country dropdown
    async function parseCSV() {
        try {
            const response = await fetch('include/pages/countries.csv');
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
        const endDate = `${year - 1}-${month.toString().padStart(2, '0')}-${new Date(year - 1, month, 0).getDate()}`;
        const url = `https://meteostat.p.rapidapi.com/point/daily?lat=${country.latitude}&lon=${country.longitude}&start=${startDate}&end=${endDate}`;

        try {
            const weatherResponse = await fetch(url, {
                method: 'GET',
                headers: {
                    'x-rapidapi-host': 'meteostat.p.rapidapi.com',
                    'x-rapidapi-key': '7fb5cc25ccmsh7ff22ca39fa4f6ep14c3eejsned96868d3b3c'
                }
            });

            if (!weatherResponse.ok) {
                throw new Error(`HTTP error! Status: ${weatherResponse.status}`);
            }

            const weatherData = await weatherResponse.json();

            if (weatherData && weatherData.data) {
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
        const weatherData = await fetchWeatherDataForMonth(year, month, country);
        window.weatherData = weatherData;
        addWeatherToDatepickers(year, month);
    }

    // Function to add weather to datepickers
    function addWeatherToDatepickers(year, month) {
        setTimeout(() => {
            $(".ui-datepicker a.ui-state-default").each(function() {
                const day = $(this).text();
                if (day) {
                    const paddedDay = day.toString().padStart(2, '0');
                    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${paddedDay}`;
                    if (window.weatherData && window.weatherData[formattedDate]) {
                        const weather = window.weatherData[formattedDate];
                        $(this).attr('data-custom', `${weather.temp}\n${weather.emoji}`);
                    }
                }
            });
        }, 100);
    }

    $(function() {
        // Initialize each datepicker separately
        $("#startDate").datepicker({
            dateFormat: "mm/dd/yy",
            minDate: 0,
            firstDay: 1,
            changeMonth: true,
            beforeShowDay: function(date) {
                return [true, date.getDay() === 0 || date.getDay() === 6 ? "weekend" : ""];
            },
            beforeShow: function() {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    const today = new Date();
                    updateWeatherData(today.getFullYear(), today.getMonth() + 1, selectedCountry);
                }
            },
            onChangeMonthYear: function(year, month) {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    updateWeatherData(year, month, selectedCountry);
                }
            },
            onSelect: function(selectedDate) {
                const date = new Date(selectedDate);
                date.setDate(date.getDate() + 1); // Set minimum end date to next day
                $("#endDate").datepicker("option", "minDate", date);
            }
        });
        
        $("#endDate").datepicker({
            dateFormat: "mm/dd/yy",
            minDate: +1, // One day from today by default
            firstDay: 1,
            changeMonth: true,
            beforeShowDay: function(date) {
                return [true, date.getDay() === 0 || date.getDay() === 6 ? "weekend" : ""];
            },
            beforeShow: function() {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    const today = new Date();
                    updateWeatherData(today.getFullYear(), today.getMonth() + 1, selectedCountry);
                }
            },
            onChangeMonthYear: function(year, month) {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    updateWeatherData(year, month, selectedCountry);
                }
            }
        });
        
        parseCSV();
    });
</script>