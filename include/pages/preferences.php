<?php
// Werte aus dem Formular der Willkommensseite abrufen
$selectedType = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
$selectedLocation = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';
$selectedDates = isset($_GET['dates']) ? htmlspecialchars($_GET['dates']) : '';

// JSON-Daten der Teilnehmer parsen, falls verfügbar
$participantsData = '{"adults":3,"children":0,"rooms":1}'; // Standardwert
if (isset($_GET['participants']) && !empty($_GET['participants'])) {
    $participantsData = $_GET['participants'];
    $participantsObj = json_decode($participantsData);
    $selectedParticipants = $participantsObj->adults . ' Adults · ' . 
                            $participantsObj->children . ' Children · ' . 
                            $participantsObj->rooms . ' Room' . 
                            ($participantsObj->rooms > 1 ? 's' : '');
} else {
    $selectedParticipants = '3 Adults · 0 Children · 1 Room';
}
?>
<div class="container">
    <h1>Preferences</h1>
    <form method="get" id="travel-form">
    <!-- Versteckte Eingabefelder zum Speichern der Daten -->
    <input type="hidden" id="dates" name="dates" value="<?php echo $selectedDates; ?>">
    <input type="hidden" id="participants" name="participants" value='<?php echo $participantsData; ?>'>

    <!-- Dropdown-Menü zur Auswahl der Reiseart -->
    <div class="form-group">
        <label for="type">Type</label>
        <select id="type" name="type">
            <option value="sightseeing" <?php echo ($selectedType == 'sightseeing') ? 'selected' : ''; ?>>Sightseeing</option>
            <option value="Beach" <?php echo ($selectedType == 'Beach') ? 'selected' : ''; ?>>Beach</option>
            <option value="Explore" <?php echo ($selectedType == 'Explore') ? 'selected' : ''; ?>>Explore</option>
            <option value="Ski" <?php echo ($selectedType == 'Ski') ? 'selected' : ''; ?>>Ski</option>
            <option value="City" <?php echo ($selectedType == 'City') ? 'selected' : ''; ?>>City Break</option>
            <option value="Cultural" <?php echo ($selectedType == 'Cultural') ? 'selected' : ''; ?>>Cultural</option>
            <option value="Adventure" <?php echo ($selectedType == 'Adventure') ? 'selected' : ''; ?>>Adventure</option>
            <option value="Relaxation" <?php echo ($selectedType == 'Relaxation') ? 'selected' : ''; ?>>Relaxation</option>
        </select>
    </div>

    <!-- Dropdown-Menü zur Auswahl des Landes -->
    <div class="form-group">
        <label for="where">Where</label>
        <select id="where">
            <option value="" disabled <?php echo empty($selectedLocation) ? 'selected' : ''; ?>>Select a country</option>
            <!-- Länder werden via JavaScript eingefügt -->
        </select>
    </div>

    <!-- Eingabefelder für die Datumsauswahl -->
    <div class="form-group">
        <label for="when">When</label>
        <div class="date-range-container">
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

    <!-- Eingabefeld für die Anzahl der Reisenden -->
    <label for="who">Who</label>
    <div class="dropdown-container">
<<<<<<< HEAD
        <div class="dropdown-toggle" id="travelerToggle"><?php echo $selectedParticipants; ?></div>
        
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
=======
        <div class="dropdown-toggle" id="travelerToggle"><?php echo $_GET['adults'] ?? "2";?> Adults · <?php echo $_GET['children'] ?? "0";?> Children · <?php echo $_GET['rooms'] ?? "1";?> Room</div>
        
        <div class="dropdown-panel" id="travelerPanel">
            <div class="counter-group">
            <span class="counter-label">Adults</span>
            <div class="counter">
                <button onclick="updateCount('adults', -1)">−</button>
                <span id="adultsCount"><?php echo $_GET['adults'] ?? "2";?></span>
                <button onclick="updateCount('adults', 1)">+</button>
            </div>
            </div>

            <div class="counter-group">
            <span class="counter-label"<>Children</span>
            <div class="counter">
                <button onclick="updateCount('children', -1)">−</button>
                <span id="childrenCount"><?php echo $_GET['children'] ?? "0";?></span>
                <button onclick="updateCount('children', 1)">+</button>
            </div>
            </div>

            <div class="counter-group">
            <span class="counter-label">Rooms</span>
            <div class="counter">
                <button onclick="updateCount('rooms', -1)">−</button>
                <span id="roomsCount"><?php echo $_GET['rooms'] ?? "1";?></span>
                <button onclick="updateCount('rooms', 1)">+</button>
            </div>
>>>>>>> pages4
            </div>

            <button class="done-button" onclick="closeDropdown()">Done</button>
            <br>
        </div>
    </div>

    <!-- Budget-Schieberegler -->
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

    <!-- Filter für Unterkunfts- und Reiseoptionen -->
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

    <!-- Navigationsschaltflächen -->
    <div class="buttons">
        <form method="get">
            <button type="button" onclick="window.location.href='index.php?page=welcome'">Back</button>
        </form>
        <form method="get">
            <button type="submit" name="page" value="Itinerary">Next</button>
        </form>
    </div>
</div>

<script>
    // Globale Variablen für das Dropdown-Menü und die Zählerstände
    const toggle = document.getElementById('travelerToggle');
    
    // Referenz zum Dropdown-Panel mit den Reisenden-Optionen
    const panel = document.getElementById('travelerPanel');

    // Standardwerte für die Anzahl der Reisenden und Zimmer
    const counts = {
        adults: <?php echo $_GET['adults'] ?? "1";?>,
        children: <?php echo $_GET['children'] ?? "1";?>,
        rooms: <?php echo $_GET['rooms'] ?? "1";?>
    };

    /**
     * Aktualisiert die Anzeige der Teilnehmerzahlen in der Benutzeroberfläche
     * und speichert die Werte im versteckten Formularfeld.
     */
    function updateDisplay() {
        // Aktualisiert die Zählerstände in der UI
        document.getElementById('adultsCount').textContent = counts.adults;
        document.getElementById('childrenCount').textContent = counts.children;
        document.getElementById('roomsCount').textContent = counts.rooms;
        
        // Aktualisiert den Text im Toggle-Button
        toggle.textContent = `${counts.adults} Adults · ${counts.children} Children · ${counts.rooms} Room${counts.rooms > 1 ? 's' : ''}`;
        
        // Speichert die Werte als JSON im versteckten Formularfeld
        document.getElementById('participants').value = JSON.stringify(counts);
    }

    /**
     * Erhöht oder verringert die Anzahl eines bestimmten Reisetyps (Erwachsene, Kinder, Zimmer).
     * Verhindert negative Werte und aktualisiert die Anzeige.
     */
    function updateCount(type, delta) {
        // Prüft, ob der neue Wert nicht negativ ist
        if (counts[type] + delta >= 0) {
            // Aktualisiert den Zählerstand
            counts[type] += delta;
            // Aktualisiert die Anzeige
            updateDisplay();
        }
    }

    /**
     * Öffnet oder schließt das Dropdown-Menü für die Reisendenzähler.
     * Event-Listener für den Klick auf den Toggle-Button.
     */
    toggle.addEventListener('click', () => {
        panel.classList.toggle('active');
    });

    /**
     * Schließt das Dropdown-Menü für die Reisendenzahlen.
     * Wird aufgerufen, wenn der "Done"-Button geklickt wird.
     */
    function closeDropdown() {
        panel.classList.remove('active');
    }

    /**
     * Initialisiert die Anzeige der Teilnehmerzahlen beim Laden der Seite.
     */
    updateDisplay();

    /**
     * Verarbeitet die Formularübermittlung und bereitet die URL-Parameter vor.
     * Führt Validierungen durch, bevor das Formular abgesendet wird.
     */
    document.getElementById('travel-form').addEventListener('submit', function(e) {
        // Verhindert die Standard-Formularübermittlung
        e.preventDefault();
        
        // Formatiert die ausgewählten Länderdaten vor der Übermittlung
        const whereSelect = document.getElementById('where');
        
        // Überprüft, ob ein Land ausgewählt wurde
        if (!whereSelect.value) {
            alert("Please select a country");
            return;
        }
        
        // Überprüft, ob beide Daten ausgewählt wurden
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            alert("Please select both check-in and check-out dates");
            return;
        }
        
        // Kombiniert Daten für die Rückwärtskompatibilität
        document.getElementById('dates').value = startDate + " - " + endDate;
        
        // Parst die Länder-JSON und extrahiert den Ländernamen
        const countryData = JSON.parse(whereSelect.value);
        
        // Erstellt die URL mit korrekten Parametern
        let url = 'index.php?';
        url += 'type=' + encodeURIComponent(document.getElementById('type').value);
        url += '&location=' + encodeURIComponent(countryData.country);
        url += '&dates=' + encodeURIComponent(document.getElementById('dates').value);
        url += '&startDate=' + encodeURIComponent(startDate);
        url += '&endDate=' + encodeURIComponent(endDate);
        url += '&participants=' + encodeURIComponent(document.getElementById('participants').value);
        url += '&page=preferences';
        
        // Navigiert zur erstellten URL
        window.location.href = url;
    });

    /**
     * Parst die CSV-Datei mit Länderdaten und befüllt die Länderauswahl.
     * Die CSV-Datei enthält Längengrad, Breitengrad und Ländername für jedes Land.
     */
    async function parseCSV() {
        try {
            // Lädt die CSV-Datei
            const response = await fetch('include/pages/countries.csv');
            const csvText = await response.text();
            
            // Teilt die CSV in Zeilen und überspringt die Kopfzeile
            const rows = csvText.split('\n').slice(1);

            // Verarbeitet jede Zeile und erstellt ein Länder-Array
            const countries = rows
                .map(row => {
                    // Teilt die Zeile in Längengrad, Breitengrad und Ländername
                    const [longitude, latitude, country] = row.split(',');
                    
                    // Validiert die Zeilenstruktur
                    if (!longitude || !latitude || !country) {
                        console.warn('Ungültige Zeile übersprungen:', row);
                        return null;
                    }
                    
                    // Erstellt ein Länder-Objekt mit Koordinaten
                    return {
                        longitude: parseFloat(longitude),
                        latitude: parseFloat(latitude),
                        country: country.trim()
                    };
                })
                .filter(country => country !== null); // Entfernt ungültige Zeilen

            // Befüllt die Länderauswahl mit den geladenen Daten
            populateCountrySelect(countries);
        } catch (error) {
            console.error('Fehler beim Parsen der CSV-Datei:', error);
        }
    }

    /**
     * Befüllt die Länderauswahl mit Optionen basierend auf den geladenen Länderdaten.
     * Fügt auch einen Event-Listener hinzu, um Wetterdaten zu laden, wenn ein Land ausgewählt wird.
     */
    function populateCountrySelect(countries) {
        const countrySelect = document.getElementById('where');
        
        // Fügt jedes Land als Option zum Select-Element hinzu
        countries.forEach(country => {
            const option = document.createElement('option');
            
            // Speichert Längen- und Breitengrad sowie Ländername als JSON im value-Attribut
            option.value = JSON.stringify({
                longitude: country.longitude,
                latitude: country.latitude,
                country: country.country
            });
            
            // Setzt den Ländernamen als angezeigten Text
            option.textContent = country.country;
            countrySelect.appendChild(option);
        });

        // Fügt Event-Listener hinzu, um Wetterdaten zu aktualisieren, wenn ein Land ausgewählt wird
        countrySelect.addEventListener('change', function () {
            // Parst die ausgewählten Länderinformationen aus dem value-Attribut
            const selectedCountry = JSON.parse(this.value);
            
            // Ermittelt das aktuelle Jahr und den aktuellen Monat
            const year = new Date().getFullYear();
            const month = new Date().getMonth() + 1; // Monate sind 0-indiziert
            
            // Aktualisiert Wetterdaten für das ausgewählte Land
            updateWeatherData(year, month, selectedCountry);
        });
    }

    /**
     * Ruft Wetterdaten für einen bestimmten Monat und ein bestimmtes Land von der Meteostat-API ab.
     * Verwendet Daten aus dem Vorjahr und passt die Datumsangaben an.
     */
    async function fetchWeatherDataForMonth(year, month, country) {
        // Erstellt Start- und Enddatum für die API-Anfrage (verwendet Vorjahresdaten)
        const startDate = `${year - 1}-${month.toString().padStart(2, '0')}-01`;
        const endDate = `${year - 1}-${month.toString().padStart(2, '0')}-${new Date(year - 1, month, 0).getDate()}`;
        
        // Erstellt API-URL mit den Koordinaten des Landes und dem Datumsbereich
        const url = `https://meteostat.p.rapidapi.com/point/daily?lat=${country.latitude}&lon=${country.longitude}&start=${startDate}&end=${endDate}`;

        try {
            // Sendet die API-Anfrage mit den erforderlichen Header-Informationen
            const weatherResponse = await fetch(url, {
                method: 'GET',
                headers: {
                    'x-rapidapi-host': 'meteostat.p.rapidapi.com',
                    'x-rapidapi-key': '7fb5cc25ccmsh7ff22ca39fa4f6ep14c3eejsned96868d3b3c'
                }
            });

            // Überprüft, ob die Anfrage erfolgreich war
            if (!weatherResponse.ok) {
                throw new Error(`HTTP-Fehler! Status: ${weatherResponse.status}`);
            }

            // Parst die JSON-Antwort
            const weatherData = await weatherResponse.json();

            // Überprüft, ob die Antwort Daten enthält
            if (weatherData && weatherData.data) {
                // Verarbeitet die Wetterdaten und speichert sie als globales Objekt
                window.weatherData = weatherData.data.reduce((acc, day) => {
                    // Fügt ein Jahr zum Datum hinzu, da wir Vorjahresdaten verwenden
                    const formattedDate = addOneYear(day.date);
                    
                    // Bestimmt die Wetterbedingungen basierend auf den Messwerten
                    const conditions = [];
                    if (day.prcp > 0) conditions.push("🌧️");
                    else if (day.snow > 0) conditions.push("❄️");
                    else if (day.wspd > 30) conditions.push("💨");
                    else if (day.tsun > 5 * 60) conditions.push("☀️");
                    else if ('tsun' in day) conditions.push("☁️");

                    // Erstellt einen lesbaren Wetterbericht oder 'N/A' wenn keine Bedingungen erkannt wurden
                    const result = conditions.length > 0 ? conditions.join(", ") : "N/A";
                    
                    // Speichert Temperatur und Wettersymbol für das aktuelle Datum
                    acc[formattedDate] = {
                        temp: `${day.tavg}°C`,
                        emoji: `${result}`
                    };
                    return acc;
                }, {});
                
                return window.weatherData;
            }
        } catch (error) {
            console.error('Fehler beim Abrufen der Wetterdaten:', error);
        }
        
        // Gibt ein leeres Objekt zurück, wenn keine Daten verfügbar sind
        return {};
    }

    /**
     * Fügt einem Datum genau ein Jahr hinzu und formatiert es als YYYY-MM-DD.
     * Diese Funktion wird verwendet, um Wetterdaten vom Vorjahr auf das aktuelle Jahr zu übertragen.
     */
    function addOneYear(formattedDate) {
        // Konvertiert den Datums-String in ein Date-Objekt
        const date = new Date(formattedDate);
        
        // Fügt ein Jahr zum Datum hinzu
        date.setFullYear(date.getFullYear() + 1);

        // Formatiert das neue Datum wieder als YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Monate sind 0-basiert
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    /**
     * Aktualisiert die Wetterdaten für ein bestimmtes Jahr, Monat und Land.
     * Ruft die API ab und fügt die Wetterdaten dann dem Datepicker hinzu.
     */
    async function updateWeatherData(year, month, country) {
        // Ruft Wetterdaten von der API ab
        const weatherData = await fetchWeatherDataForMonth(year, month, country);
        
        // Speichert die Daten global für spätere Verwendung
        window.weatherData = weatherData;
        
        // Fügt die Wetterdaten zu den Datepicker-Kalendern hinzu
        addWeatherToDatepickers(year, month);
    }

    /**
     * Fügt Wetterdaten zu den Datepicker-Kalendern hinzu, indem für jeden Tag 
     * das data-custom-Attribut gesetzt wird.
     * Verwendet einen Timeout, um sicherzustellen, dass der Datepicker vollständig gerendert ist.
     */
    function addWeatherToDatepickers(year, month) {
        // Verwendet einen Timeout, um sicherzustellen, dass der Datepicker gerendert wurde
        setTimeout(() => {
            // Durchläuft alle Tage im Datepicker-Kalender
            $(".ui-datepicker a.ui-state-default").each(function() {
                // Liest den Tag aus dem Element-Text
                const day = $(this).text();
                
                // Prüft, ob ein Tag vorhanden ist
                if (day) {
                    // Formatiert den Tag mit führender Null
                    const paddedDay = day.toString().padStart(2, '0');
                    
                    // Erstellt ein Datumsformat YYYY-MM-DD für die Wetterabfrage
                    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${paddedDay}`;
                    
                    // Wenn Wetterdaten für diesen Tag vorhanden sind, werden sie als Attribut gesetzt
                    if (window.weatherData && window.weatherData[formattedDate]) {
                        const weather = window.weatherData[formattedDate];
                        
                        // Setzt Temperatur und Emoji als data-custom-Attribut mit Zeilenumbruch
                        $(this).attr('data-custom', `${weather.temp}\n${weather.emoji}`);
                    }
                }
            });
        }, 100); // Wartet 100ms, um sicherzustellen, dass der Datepicker gerendert wurde
    }

    /**
     * jQuery-Funktion zur Initialisierung der Datepicker für Anfangs- und Enddatum.
     * Konfiguriert verschiedene Datepicker-Optionen und Ereignisbehandlungen.
     */
    $(function() {
        // Initialisierung des Startdatum-Datepickers
        $("#startDate").datepicker({
            dateFormat: "mm/dd/yy",
            minDate: 0, // Heute als frühestes Datum
            firstDay: 1, // Montag als erster Tag der Woche
            changeMonth: true, // Ermöglicht die Änderung des Monats
            beforeShowDay: function(date) {
                // Hebt Wochenenden (Samstag und Sonntag) hervor
                return [true, date.getDay() === 0 || date.getDay() === 6 ? "weekend" : ""];
            },
            beforeShow: function() {
                // Lädt Wetterdaten, wenn der Kalender angezeigt wird
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    const today = new Date();
                    updateWeatherData(today.getFullYear(), today.getMonth() + 1, selectedCountry);
                }
            },
            onChangeMonthYear: function(year, month) {
                // Aktualisiert Wetterdaten, wenn der Monat oder das Jahr geändert wird
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    updateWeatherData(year, month, selectedCountry);
                }
            },
            onSelect: function(selectedDate) {
                // Setzt das Mindest-Enddatum auf den Tag nach dem ausgewählten Startdatum
                const date = new Date(selectedDate);
                date.setDate(date.getDate() + 1);
                $("#endDate").datepicker("option", "minDate", date);
            }
        });
        
        // Initialisierung des Enddatum-Datepickers
        $("#endDate").datepicker({
            dateFormat: "mm/dd/yy",
            minDate: +1, // Ein Tag ab heute als Standard
            firstDay: 1, // Montag als erster Tag der Woche
            changeMonth: true, // Ermöglicht die Änderung des Monats
            beforeShowDay: function(date) {
                // Hebt Wochenenden (Samstag und Sonntag) hervor
                return [true, date.getDay() === 0 || date.getDay() === 6 ? "weekend" : ""];
            },
            beforeShow: function() {
                // Lädt Wetterdaten, wenn der Kalender angezeigt wird
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    const today = new Date();
                    updateWeatherData(today.getFullYear(), today.getMonth() + 1, selectedCountry);
                }
            },
            onChangeMonthYear: function(year, month) {
                // Aktualisiert Wetterdaten, wenn der Monat oder das Jahr geändert wird
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    updateWeatherData(year, month, selectedCountry);
                }
            }
        });
        
        // Startet den CSV-Parsing-Prozess, um Länderdaten zu laden
        parseCSV();
    });

    /**
     * Aktualisiert den Wert und die Position des Budget-Sliders.
     * Diese Funktion wird aufgerufen, wenn der Slider verschoben wird.
     */
    function updateBudgetValue() {
        let budget = document.getElementById("budget");
        let budgetValue = document.getElementById("budgetValue");
        let value = budget.value;
        
        // Aktualisiert den angezeigten Budgetwert
        budgetValue.textContent = value + "€";
        
        // Berechnet die Position des Wertlabels basierend auf dem Prozentsatz des Sliders
        let percent = ((value - budget.min) / (budget.max - budget.min)) * 100;
        
        // Positioniert das Label über dem Slider-Griff
        budgetValue.style.left = `calc(${percent}% + (${8 - percent * 0.15}px))`;
    }

    /**
     * Initialisiert den Budget-Slider-Wert beim Laden der Seite.
     */
    document.addEventListener("DOMContentLoaded", function () {
        updateBudgetValue();
    });

    /**
     * jQuery-Funktion, die ausgeführt wird, wenn das Dokument vollständig geladen ist.
     * Initialisiert Formularwerte aus URL-Parametern.
     */
    $(document).ready(function() {
        // Liest URL-Parameter aus
        const urlParams = new URLSearchParams(window.location.search);
        const locationParam = urlParams.get('location');
        const startDateParam = urlParams.get('startDate');
        const endDateParam = urlParams.get('endDate');
        const participantsParam = urlParams.get('participants');
        
        // Wenn ein Standort-Parameter vorhanden ist, versucht das entsprechende Land auszuwählen
        if (locationParam) {
            // Wartet darauf, dass die Länder geladen werden
            const checkCountriesLoaded = setInterval(function() {
                const countrySelect = document.getElementById('where');
                
                // Prüft, ob die Länder geladen wurden
                if (countrySelect.options.length > 1) {
                    clearInterval(checkCountriesLoaded);
                    
                    // Sucht nach einem passenden Land in der Liste
                    let foundMatch = false;
                    Array.from(countrySelect.options).forEach(option => {
                        if (option.value && option.value !== 'null') {
                            try {
                                const countryData = JSON.parse(option.value);
                                
                                // Wenn ein Land mit dem gleichen Namen gefunden wird (Groß-/Kleinschreibung ignorieren)
                                if (countryData.country && countryData.country.toLowerCase() === locationParam.toLowerCase()) {
                                    countrySelect.value = option.value;
                                    foundMatch = true;
                                    
                                    // Löst das Change-Ereignis aus, um Wetterdaten zu aktualisieren
                                    const event = new Event('change');
                                    countrySelect.dispatchEvent(event);
                                }
                            } catch (e) {
                                console.error("Fehler beim Parsen der Länderdaten:", e);
                            }
                        }
                    });
                    
                    // Wenn kein Match gefunden wurde, erstellt eine neue Option für das Land
                    if (!foundMatch && locationParam) {
                        const option = document.createElement('option');
                        option.textContent = locationParam;
                        option.value = JSON.stringify({
                            longitude: 0,
                            latitude: 0,
                            country: locationParam
                        });
                        option.selected = true;
                        countrySelect.appendChild(option);
                    }
                }
            }, 100); // Überprüft alle 100ms
        }
        
        // Initialisiert die Daten aus den URL-Parametern
        if (startDateParam) {
            $("#startDate").datepicker("setDate", new Date(startDateParam));
        }
        
        if (endDateParam) {
            $("#endDate").datepicker("setDate", new Date(endDateParam));
        }
        
        // Initialisiert die Teilnehmerzahlen, falls vorhanden
        if (participantsParam) {
            try {
                const participantsObj = JSON.parse(participantsParam);
                
                // Aktualisiert das counts-Objekt
                counts.adults = participantsObj.adults || 3;
                counts.children = participantsObj.children || 0;
                counts.rooms = participantsObj.rooms || 1;
                
                // Aktualisiert die Anzeige
                updateDisplay();
            } catch (e) {
                console.error("Fehler beim Parsen der Teilnehmerdaten:", e);
            }
        }
    });
</script>