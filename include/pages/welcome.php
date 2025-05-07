<!-- Search Container -->
<div id="search-container" class="search-container">
    <input type="text" id="vacation-type" class="top-input"
           placeholder="What type of vacation? (Beach, Explore, Ski, ...)" name="type">
    <div class="bottom-row">
        <input type="text" id="where" class="search-input" placeholder="Where?" name="location">
        <select id="where">
            <option value="" disabled selected>Select a country</option>
        </select>
        <input type="text" id="datepicker" class="search-input" placeholder="When?" name="dates">
        <!-- People selector -->
        <div class="dropdown-container">
            <div class="dropdown-toggle" id="travelerToggle">2 Adults · 0 Children · 1 Room</div>

            <div class="dropdown-panel" id="travelerPanel">
                <div class="counter-group">
                    <span class="counter-label">Adults</span>
                    <div class="counter">
                        <button onclick="updateCount('adults', -1)">−</button>
                        <span id="adultsCount">2</span>
                        <button onclick="updateCount('adults', 1)">+</button>
                    </div>
                </div>

                <div class="counter-group">
                    <span class="counter-label">Children</span>
                    <div class="counter">
                        <button onclick="updateCount('children', -1)">−</button>
                        <span id="childrenCount">0</span>
                        <button onclick="updateCount('children', 1)">+</button>
                    </div>
                </div>

                <div class="counter-group">
                    <span class="counter-label">Rooms</span>
                    <div class="counter">
                        <button onclick="updateCount('rooms', -1)">−</button>
                        <span id="roomsCount">1</span>
                        <button onclick="updateCount('rooms', 1)">+</button>
                    </div>
                </div>

                <button class="done-button" onclick="closeDropdown()">Done</button>
                <br>
            </div>
        </div>
    </div>
    <form method="get">
        <button type="submit" id="search-button" class="search-button" name="page" value="preferences">GO</button>
        <input type="hidden" id="adultsCountGet" name="adults" value="2">
        <input type="hidden" id="childrenCountGet" name="children" value="0">
        <input type="hidden" id="roomsCountGet" name="rooms" value="1">
    </form>
</div>
<script>
    const toggle = document.getElementById('travelerToggle')
    const panel = document.getElementById('travelerPanel')

    const counts = {
        adults: 2,
        children: 0,
        rooms: 1
    }

    toggle.addEventListener('click', () => {
        panel.classList.toggle('active')
    })

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
</script>
<style>
    /* Highlight weekends in the datepicker */
    .ui-datepicker .weekend .ui-state-default {
        background: #FEA;
    }
</style>