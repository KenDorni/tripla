<div class="container">
    <form method="get" id="travel-form">
        <!-- Erste Zeile: Typ und Ort -->
        <div class="form-row">
            <!-- Dropdown-Menü zur Auswahl der Reiseart -->
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

            <!-- Dropdown-Menü zur Auswahl des Landes -->
            <div class="form-col">
                <div class="form-group">
                    <label for="where">Where</label>
                    <select id="where" name="location">
                        <option value="" disabled selected>Select a country</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Zweite Zeile: Wann und Wer -->
        <div class="form-row">
            <!-- Eingabefelder für Anfangs- und Enddatum -->
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

            <!-- Eingabefeld für die Anzahl der Reisenden -->
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

        <!-- Verstecktes Eingabefeld zum Speichern der Teilnehmerdaten -->
        <input type="hidden" id="participants" name="participants" value="">
        <!-- Verstecktes Eingabefeld zum Kombinieren der Daten -->
        <input type="hidden" id="dates" name="dates" value="">
        
        <!-- Suchknopf zum Aufrufen der Einstellungsseite -->
        <button type="submit" id="search-button" class="search-button" name="page" value="preferences">GO</button>
    </form>
</div>

<script>
    /**
     * Globale Variablen für das Dropdown-Menü und die Zählerstände
     * Diese Variablen steuern die Benutzeroberfläche für die Reisendenzahlen
     */
    const toggle = document.getElementById('travelerToggle');
    const panel = document.getElementById('travelerPanel');
    const counts = {
        adults: 3,
        children: 0,
        rooms: 1
    };

    /**
     * Aktualisiert die Anzeige der Teilnehmerzahlen
     * 
     * Diese Funktion aktualisiert alle sichtbaren Zähler im UI sowie das versteckte Eingabefeld,
     * das die Teilnehmerdaten für die Formularübermittlung speichert.
     * Sie wird jedes Mal aufgerufen, wenn sich die Anzahl der Reisenden ändert.
     */
    function updateDisplay() {
        // Aktualisiere die sichtbaren Zählerstände im Panel
        document.getElementById('adultsCount').textContent = counts.adults;
        document.getElementById('childrenCount').textContent = counts.children;
        document.getElementById('roomsCount').textContent = counts.rooms;
        
        // Aktualisiere den Text im Toggle-Button 
        // Beachte die korrekte Pluralform für 'Room/Rooms'
        toggle.textContent = `${counts.adults} Adults · ${counts.children} Children · ${counts.rooms} Room${counts.rooms > 1 ? 's' : ''}`;
        
        // Speichere die Daten als JSON im versteckten Eingabefeld für die Formularübermittlung
        document.getElementById('participants').value = JSON.stringify(counts);
    }

    /**
     * Erhöht oder verringert die Anzahl eines bestimmten Typs von Reisenden
     * 
     * Diese Funktion wird durch die Plus- und Minus-Buttons im Dropdown aufgerufen
     * und verändert die entsprechenden Zählerstände. Sie verhindert auch negative Werte.
     */
    function updateCount(type, delta) {
        // Verhindere negative Werte
        if (counts[type] + delta >= 0) {
            // Aktualisiere den Zählerstand
            counts[type] += delta;
            // Aktualisiere die Anzeige mit den neuen Werten
            updateDisplay();
        }
    }

    /**
     * Event-Listener für den Toggle-Button des Dropdown-Menüs
     * 
     * Dieser Listener schaltet das Dropdown-Panel ein oder aus, wenn der Benutzer
     * auf den Toggle-Button klickt. Die Anzeige wird durch das Hinzufügen oder Entfernen
     * der 'active'-Klasse gesteuert.
     */
    toggle.addEventListener('click', () => {
        panel.classList.toggle('active');
    });

    /**
     * Schließt das Dropdown-Menü für die Reisendenzahlen
     * 
     * Diese Funktion wird aufgerufen, wenn der Benutzer den 'Done'-Button innerhalb
     * des Panels klickt, um die Auswahl zu bestätigen und das Panel zu schließen.
     */
    function closeDropdown() {
        panel.classList.remove('active');
    }

    /**
     * Aktualisiert die Anzeige beim ersten Laden der Seite, damit die
     * Standardwerte im UI und im versteckten Eingabefeld korrekt angezeigt werden.
     */
    updateDisplay();

    /**
     * Event-Listener für die Formularübermittlung
     * 
     * Dieser Listener übernimmt die Kontrolle über die Formularübermittlung,
     * führt Validierungen durch und erstellt eine URL mit allen erforderlichen Parametern.
     */
    document.getElementById('travel-form').addEventListener('submit', function(e) {
        // Verhindere die Standard-Formularübermittlung
        e.preventDefault();
        
        // Hole das ausgewählte Land
        const whereSelect = document.getElementById('where');
        
        // Validiere, ob ein Land ausgewählt wurde
        if (!whereSelect.value) {
            alert("Please select a country");
            return; // Stoppe die Ausführung, wenn kein Land ausgewählt wurde
        }
        
        // Hole die ausgewählten Datumsangaben
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        // Validiere, ob beide Daten ausgewählt wurden
        if (!startDate || !endDate) {
            alert("Please select both check-in and check-out dates");
            return; // Stoppe die Ausführung, wenn Daten fehlen
        }
        
        // Kombiniere die Daten für Rückwärtskompatibilität mit älteren Funktionen
        document.getElementById('dates').value = startDate + " - " + endDate;
        
        // Parse die JSON-Daten des ausgewählten Landes
        const countryData = JSON.parse(whereSelect.value);
        
        // Baue die URL mit allen erforderlichen Parametern auf
        let url = 'index.php?';
        url += 'type=' + encodeURIComponent(document.getElementById('type').value);
        url += '&location=' + encodeURIComponent(countryData.country);
        url += '&dates=' + encodeURIComponent(document.getElementById('dates').value);
        url += '&startDate=' + encodeURIComponent(startDate);
        url += '&endDate=' + encodeURIComponent(endDate);
        url += '&participants=' + encodeURIComponent(document.getElementById('participants').value);
        url += '&page=preferences';
        
        // Navigiere zur erstellten URL
        window.location.href = url;
    });

    /**
     * Parst die CSV-Datei mit Länderdaten und füllt das Länder-Dropdown
     * 
     * Diese asynchrone Funktion lädt eine CSV-Datei mit Länderinformationen,
     * verarbeitet deren Inhalt und füllt das Dropdown-Menü mit den Ländern.
     */
    async function parseCSV() {
        try {
            // Laden der CSV-Datei
            const response = await fetch('include/pages/countries.csv');
            const csvText = await response.text();
            
            // Aufteilen in Zeilen und Überspringen der Kopfzeile
            const rows = csvText.split('\n').slice(1);

            // Verarbeite jede Zeile, um die Länderdaten zu extrahieren
            const countries = rows
                .map(row => {
                    // Teile die Zeile in Bestandteile auf (Längengrad, Breitengrad, Land)
                    const [longitude, latitude, country] = row.split(',');
                    
                    // Validiere die Zeile
                    if (!longitude || !latitude || !country) {
                        console.warn('Invalid row skipped:', row);
                        return null; // Überspringe ungültige Zeilen
                    }
                    
                    // Erstelle ein Länder-Objekt mit den extrahierten Daten
                    return {
                        longitude: parseFloat(longitude),
                        latitude: parseFloat(latitude),
                        country: country.trim()
                    };
                })
                .filter(country => country !== null); // Entferne ungültige Zeilen

            // Befülle das Dropdown-Menü mit den geladenen Länderdaten
            populateCountrySelect(countries);
        } catch (error) {
            console.error('Error parsing CSV file:', error);
        }
    }

    /**
     * Befüllt das Dropdown-Menü für Länder mit Optionen
     * 
     * Diese Funktion erstellt für jedes Land eine Option im Dropdown-Menü
     * und fügt einen Event-Listener hinzu, der die Wetterdaten aktualisiert,
     * wenn ein Land ausgewählt wird.
     */
    function populateCountrySelect(countries) {
        // Referenz zum Länderauswahl-Element
        const countrySelect = document.getElementById('where');
        
        // Erstelle für jedes Land eine Option im Dropdown
        countries.forEach(country => {
            const option = document.createElement('option');
            
            // Speichere die Länderdaten als JSON im value-Attribut
            option.value = JSON.stringify({
                longitude: country.longitude,
                latitude: country.latitude,
                country: country.country
            });
            
            // Setze den Ländernamen als angezeigten Text
            option.textContent = country.country;
            countrySelect.appendChild(option);
        });

        // Füge einen Event-Listener hinzu, der Wetterdaten lädt, wenn ein Land ausgewählt wird
        countrySelect.addEventListener('change', function () {
            // Parse die ausgewählten Länderdaten
            const selectedCountry = JSON.parse(this.value);
            
            // Ermittle das aktuelle Jahr und den aktuellen Monat
            const year = new Date().getFullYear();
            const month = new Date().getMonth() + 1; // JavaScript-Monate beginnen bei 0
            
            // Aktualisiere die Wetterdaten für das ausgewählte Land
            updateWeatherData(year, month, selectedCountry);
        });
    }

    /**
     * Ruft Wetterdaten für einen bestimmten Monat und ein bestimmtes Land ab
     * 
     * Diese asynchrone Funktion verwendet die Meteostat API, um historische Wetterdaten
     * für den angegebenen Monat und das Land abzurufen. Da aktuelle Wetterdaten nicht
     * verfügbar sind, werden Daten vom Vorjahr verwendet und das Datum angepasst.
     */
    async function fetchWeatherDataForMonth(year, month, country) {
        // Erstelle Start- und Enddatum für die API-Anfrage (vom Vorjahr)
        const startDate = `${year - 1}-${month.toString().padStart(2, '0')}-01`;
        const endDate = `${year - 1}-${month.toString().padStart(2, '0')}-${new Date(year - 1, month, 0).getDate()}`;
        
        // Erstelle die API-URL mit den Koordinaten und dem Datumsbereich
        const url = `https://meteostat.p.rapidapi.com/point/daily?lat=${country.latitude}&lon=${country.longitude}&start=${startDate}&end=${endDate}`;

        try {
            // Sende die API-Anfrage mit den erforderlichen Headers
            const weatherResponse = await fetch(url, {
                method: 'GET',
                headers: {
                    'x-rapidapi-host': 'meteostat.p.rapidapi.com',
                    'x-rapidapi-key': '7fb5cc25ccmsh7ff22ca39fa4f6ep14c3eejsned96868d3b3c'
                }
            });

            // Überprüfe, ob die Anfrage erfolgreich war
            if (!weatherResponse.ok) {
                throw new Error(`HTTP error! Status: ${weatherResponse.status}`);
            }

            // Parse die JSON-Antwort
            const weatherData = await weatherResponse.json();

            // Prüfe, ob die Antwort Daten enthält
            if (weatherData && weatherData.data) {
                // Verarbeite die Wetterdaten und speichere sie global
                window.weatherData = weatherData.data.reduce((acc, day) => {
                    // Füge ein Jahr zum Datum hinzu (da wir Vorjahresdaten verwenden)
                    const formattedDate = addOneYear(day.date);
                    
                    // Bestimme das Wetter-Emoji basierend auf den Wetterdaten
                    const conditions = [];
                    if (day.prcp > 0) conditions.push("🌧️");          // Regen
                    else if (day.snow > 0) conditions.push("❄️");      // Schnee
                    else if (day.wspd > 30) conditions.push("💨");     // Wind
                    else if (day.tsun > 5 * 60) conditions.push("☀️"); // Sonnig
                    else if ('tsun' in day) conditions.push("☁️");     // Bewölkt

                    // Erstelle einen lesbaren Wetterbericht oder 'N/A' wenn keine Daten
                    const result = conditions.length > 0 ? conditions.join(", ") : "N/A";
                    
                    // Speichere die formatierten Wetterdaten in der Akkumulator-Struktur
                    acc[formattedDate] = {
                        temp: `${day.tavg}°C`,    // Durchschnittstemperatur
                        emoji: `${result}`         // Wetter-Emoji
                    };
                    
                    return acc;
                }, {});
                
                return window.weatherData;
            }
        } catch (error) {
            console.error('Error fetching weather data:', error);
        }
        
        // Gib ein leeres Objekt zurück, wenn ein Fehler auftritt
        return {};
    }

    /**
     * Fügt einem Datum ein Jahr hinzu und formatiert es
     * 
     * Diese Hilfsfunktion nimmt ein Datum im Format YYYY-MM-DD und fügt ein Jahr hinzu,
     * um Vorjahresdaten auf das aktuelle Jahr zu übertragen. Das angepasste Datum
     * wird im gleichen Format zurückgegeben.
     */
    function addOneYear(formattedDate) {
        // Erstelle ein Date-Objekt aus dem Datums-String
        const date = new Date(formattedDate);
        
        // Füge ein Jahr hinzu
        date.setFullYear(date.getFullYear() + 1);

        // Formatiere das neue Datum wieder als YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Monate sind 0-basiert
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    /**
     * Aktualisiert Wetterdaten und wendet sie auf den Datepicker an
     * 
     * Diese asynchrone Funktion ruft Wetterdaten für ein bestimmtes Jahr, Monat und Land ab
     * und wendet sie dann auf die Datepicker an, um Wetterinformationen in den Kalendern anzuzeigen.
     */
    async function updateWeatherData(year, month, country) {
        // Hole die Wetterdaten vom API
        const weatherData = await fetchWeatherDataForMonth(year, month, country);
        
        // Speichere die Daten global
        window.weatherData = weatherData;
        
        // Wende die Wetterdaten auf die Datepicker an
        addWeatherToDatepickers(year, month);
    }

    /**
     * Fügt Wetterdaten zu den Datepicker-Kalendern hinzu
     * 
     * Diese Funktion durchläuft alle Datumsfelder im Datepicker und fügt
     * die entsprechenden Wetterdaten als data-custom-Attribut hinzu, wodurch
     * die Temperatur und das Wetter-Emoji unter dem Datum angezeigt werden.
     */
    function addWeatherToDatepickers(year, month) {
        // Verwende einen Timeout, um sicherzustellen, dass der Datepicker gerendert wurde
        setTimeout(() => {
            // Durchlaufe alle Datumsfelder im Datepicker
            $(".ui-datepicker a.ui-state-default").each(function() {
                // Hole den Tag aus dem Text des Elements
                const day = $(this).text();
                
                // Wenn ein Tag gefunden wurde
                if (day) {
                    // Formatiere den Tag mit führender Null
                    const paddedDay = day.toString().padStart(2, '0');
                    
                    // Erstelle einen Datums-String im Format YYYY-MM-DD
                    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${paddedDay}`;
                    
                    // Wenn Wetterdaten für diesen Tag vorhanden sind
                    if (window.weatherData && window.weatherData[formattedDate]) {
                        // Hole die Wetterdaten für diesen Tag
                        const weather = window.weatherData[formattedDate];
                        
                        // Setze das data-custom-Attribut mit Temperatur und Emoji
                        $(this).attr('data-custom', `${weather.temp}\n${weather.emoji}`);
                    }
                }
            });
        }, 100); // Warte 100ms, um sicherzustellen, dass der Datepicker gerendert wurde
    }

    /**
     * jQuery-Funktion zur Initialisierung der Datepicker
     * 
     * Diese Funktion wird ausgeführt, wenn das Dokument geladen ist, und initialisiert
     * die Start- und Enddatum-Datepicker mit spezifischen Optionen und Event-Handlern.
     */
    $(function() {
        // Initialisierung des Startdatum-Datepickers
        $("#startDate").datepicker({
            dateFormat: "mm/dd/yy",          // Format des Datums
            minDate: 0,                      // Mindestdatum ist heute
            firstDay: 1,                     // Woche beginnt am Montag
            changeMonth: true,               // Erlaubt das Ändern des Monats
            
            // Markiere Wochenenden (Samstag und Sonntag)
            beforeShowDay: function(date) {
                return [true, date.getDay() === 0 || date.getDay() === 6 ? "weekend" : ""];
            },
            
            // Lade Wetterdaten, wenn der Datepicker angezeigt wird
            beforeShow: function() {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    const today = new Date();
                    updateWeatherData(today.getFullYear(), today.getMonth() + 1, selectedCountry);
                }
            },
            
            // Aktualisiere Wetterdaten, wenn der Monat oder das Jahr geändert wird
            onChangeMonthYear: function(year, month) {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    updateWeatherData(year, month, selectedCountry);
                }
            },
            
            // Setze das Mindestdatum für das Enddatum auf den Tag nach dem ausgewählten Startdatum
            onSelect: function(selectedDate) {
                const date = new Date(selectedDate);
                date.setDate(date.getDate() + 1);
                $("#endDate").datepicker("option", "minDate", date);
            }
        });
        
        // Initialisierung des Enddatum-Datepickers
        $("#endDate").datepicker({
            dateFormat: "mm/dd/yy",          // Format des Datums
            minDate: +1,                     // Mindestdatum ist morgen
            firstDay: 1,                     // Woche beginnt am Montag
            changeMonth: true,               // Erlaubt das Ändern des Monats
            
            // Markiere Wochenenden (Samstag und Sonntag)
            beforeShowDay: function(date) {
                return [true, date.getDay() === 0 || date.getDay() === 6 ? "weekend" : ""];
            },
            
            // Lade Wetterdaten, wenn der Datepicker angezeigt wird
            beforeShow: function() {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    const today = new Date();
                    updateWeatherData(today.getFullYear(), today.getMonth() + 1, selectedCountry);
                }
            },
            
            // Aktualisiere Wetterdaten, wenn der Monat oder das Jahr geändert wird
            onChangeMonthYear: function(year, month) {
                const countrySelect = document.getElementById('where');
                if (countrySelect.value) {
                    const selectedCountry = JSON.parse(countrySelect.value);
                    updateWeatherData(year, month, selectedCountry);
                }
            }
        });
        
        // Starte den Prozess zum Laden der Länderdaten
        parseCSV();
    });
</script>