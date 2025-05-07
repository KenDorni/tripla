<div class="container">
    <h1>Your Travel Itinerary</h1>
    <div id="timeline" class="timeline" onchange="updateSession()"></div>
</div>

<div id="mapModal" class="overlay">
    <div class="overlay-header">
        <input id="mapSearchInput" placeholder="Search location...">
        <button onclick="closeMapModal()">Close</button>
    </div>
    <div id="searchSuggestions" class="suggestions"></div>
    <div id="map"></div>
</div>

<div id="transitOverlay" class="overlay">
    <div class="overlay-header">
        <h3>Transit Options</h3>
        <button onclick="document.getElementById('transitOverlay').style.display='none'">Close</button>
    </div>
    <div id="transitContent" style="overflow-y: auto; height: calc(100% - 50px); padding: 10px;"></div>
</div>

<div id="flightModal" class="overlay">
    <div class="overlay-header">
        <span id="flightModalTitle">Available Flights</span>
        <button onclick="closeFlightModal()">Close</button>
    </div>
    <div id="flightList" class="suggestions"></div>
</div>
<!--<button onclick="updateSession()">Save Itinerary</button>-->

<script>
    let itinerary = [{
        Type: "Itinerary_Stop",
        Value: {
            fk_itinerary_includes: 0,
            type: "start",
            value: "",
            booking_ref: "",
            link: "",
            start: "",
            stop: ""
        }
    }, {
        Type: "Itinerary_Transit",
        Value: {
            fk_itinerary_has_assigned: 0,
            method: "",
            booking_ref: "",
            link: "",
            start: "",
            stop: ""
        }
    }, {
        Type: "Itinerary_Stop",
        Value: {
            fk_itinerary_includes: 0,
            type: "stop",
            value: "",
            booking_ref: "",
            link: "",
            start: "",
            stop: ""
        }
    }]

    function isEditable() {
        const stops = itinerary.filter(item => item.Type === "Itinerary_Stop");

        const hasStart = stops.some(item => item.Value.type === "start" && item.Value.value?.trim());
        const hasEnd = stops.some(item => item.Value.type === "end" && item.Value.value?.trim());

        return hasStart && hasEnd;
    }

    function addStopBefore(index) {
        const stopId = Date.now();

        const stop = {
            Type: "Itinerary_Stop",
            Value: {
                fk_itinerary_includes: 0,
                type: "Activity",
                value: "",
                booking_ref: "",
                link: "",
                start: "",
                stop: ""
            },
            name: `stop${stopId}`
        };

        const transit = {
            Type: "Itinerary_Transit",
            Value: {
                fk_itinerary_has_assigned: 0,
                method: "Train",
                booking_ref: "",
                link: "",
                start: "",
                stop: ""
            },
            name: `transit${stopId}`
        };

        itinerary.splice(index, 0, transit, stop);
        rebuildBefore();
    }

    function removeItem(name) {
        const idx = itinerary.findIndex(i => i.name === name);
        const item = itinerary[idx];

        if (!item || item.Type === "Itinerary_Transit") return;

        const prevTransit = itinerary[idx - 1];
        if (prevTransit?.Type === "Itinerary_Transit") {
            itinerary.splice(idx - 1, 2);
        } else {
            itinerary.splice(idx, 1);
        }

        rebuildBefore();
    }

    function rebuildBefore() {
        let ordered = [];

        for (let i = 0; i < itinerary.length; i++) {
            const item = itinerary[i];
            item.before = i === 0 ? null : i - 1;
            ordered.push(item);
        }

        itinerary = ordered;
        renderItinerary();
    }

    function updateTime(name, value, field) {
        const item = itinerary.find(i => i.name === name);
        if (item) item.Value[field] = value;
    }

    function updateCategory(name, value) {
        const item = itinerary.find(i => i.name === name);
        if (item) {
            if (item.Type === "Itinerary_Transit") {
                item.Value.method = value;
            } else {
                item.Value.type = value;
            }
        }
    }

    function renderItinerary() {
        const container = document.getElementById("timeline");
        container.innerHTML = "";

        const line = document.createElement("div");
        line.className = "center-line";
        container.appendChild(line);

        itinerary.forEach((item, index) => {
            const isTransit = item.Type === "Itinerary_Transit";
            const name = item.name || `item${index}`;
            item.name = name;

            // Add "+" button between stops
            if (!isTransit ) {
                const addRow = document.createElement("div");
                addRow.className = "add-section";
                const btn = document.createElement("button");
                btn.textContent = "+";
                btn.onclick = () => addStopBefore(index + 1);
                addRow.appendChild(btn);
                container.appendChild(addRow);
            }

            const row = document.createElement("div");
            row.className = "entry";
            row.dataset.name = name;

            const left = document.createElement("div");
            left.className = "left";
            left.innerHTML = `
            <input type="datetime-local" value="${item.Value.start || ''}" onchange="updateTime('${name}', this.value, 'start')">
            <input type="datetime-local" value="${item.Value.stop || ''}" onchange="updateTime('${name}', this.value, 'stop')">
        `;

            const controls = document.createElement("div");
            controls.className = "controls";
            if (item.Type !== "Itinerary_Transit" && isEditable()) {
                const removeBtn = document.createElement("button");
                removeBtn.textContent = "–";
                removeBtn.onclick = () => removeItem(name);
                controls.appendChild(removeBtn);
            }

            const right = document.createElement("div");
            right.className = "right";

            const safeValue = item.Value.value || '';
            const safeType = item.Type === "Itinerary_Transit" ? "transit" : (item.Value.type || '');
            const safeCategory = item.Type === "Itinerary_Transit" ? item.Value.method : (item.Value.type || '');

            let inputHTML = `
            <input class="location-search" placeholder="Click to pick..." readonly
                   onclick="openHandler('${name}', '${safeType}', '${safeCategory}')"
                   value="${safeValue}">
        `;

            let categoryOptions;
            if (item.Type === "Itinerary_Transit") {
                categoryOptions = ['Flight', 'Train', 'Car', 'Bus', 'Taxi'];
            } else {
                categoryOptions = ['Location', 'Stay', 'Activity'];
            }

            right.innerHTML = inputHTML + `
            <select onchange="updateCategory('${name}', this.value)">
                ${categoryOptions.map(opt => `
                    <option value="${opt}" ${safeCategory === opt ? 'selected' : ''}>${opt}</option>
                `).join('')}
            </select>
        `;

            right.appendChild(controls);
            row.appendChild(left);
            row.appendChild(right);
            container.appendChild(row);
        });
    }

    function openHandler(name, type, category) {
        if (type === 'transit') {
            showTransportOptions(name, category);
        } else if (category === 'Location') {
            openMap(name)
        } else if (category === 'Stay') {
            searchHotelsAround(name)
        } else if (category === 'Activity') {
            alert(`Search activities for ${name}`)
        }
    }

    function showTransportOptions(name, category){
        if (category === 'Flight'){
            showFlightOptions(name);
        }else{
            alert('Not implemented')
        }
    }


    let map
    let marker
    let selectedInputName = null
    let currentEditName

    function openMap(name) {
        selectedInputName = name
        const modal = document.getElementById("mapModal")
        modal.style.display = "block"

        if (!map) {
            map = L.map('map').setView([48.8566, 2.3522], 5) // Default center: Paris
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map)

            map.on('click', function (e) {
                if (marker) marker.remove()
                marker = L.marker(e.latlng).addTo(map)
            })
        }

        document.getElementById("mapSearchInput").oninput = async function () {
            const query = this.value
            const results = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`).then(res => res.json())
            const suggestions = document.getElementById("searchSuggestions")
            suggestions.innerHTML = ""

            results.slice(0, 5).forEach(loc => {
                const div = document.createElement("div")
                div.textContent = loc.display_name
                div.onclick = () => {
                    if (marker) marker.remove()
                    const latlng = [parseFloat(loc.lat), parseFloat(loc.lon)]
                    map.setView(latlng, 13)
                    marker = L.marker(latlng).addTo(map)
                    const item = itinerary.find(i => i.name === selectedInputName)
                    if (item) item.value = loc.display_name
                    closeMapModal()
                    renderItinerary()
                }
                suggestions.appendChild(div)
            })
        }
    }

    function findNearestLocation(index, direction) {
        let i = index + direction;
        while (i >= 0 && i < itinerary.length) {
            const item = itinerary[i];
            if (item.type !== 'transit' && item.value) {
                return item;
            }
            i += direction;
        }
        return null;
    }

    function showFlightOptions(transitName) {
        const index = itinerary.findIndex(i => i.name === transitName);
        const prev = itinerary[index - 1];
        const next = itinerary[index + 1];

        if (!prev || !next) {
            alert("Cannot determine flight origin or destination.");
            return;
        }

        const date = itinerary[index].startTime?.slice(0, 10) || new Date().toISOString().slice(0, 10);
        const flights = [
            `Flight from ${prev.value} to ${next.value} at 08:00 on ${date}`,
            `Flight from ${prev.value} to ${next.value} at 13:00 on ${date}`,
            `Flight from ${prev.value} to ${next.value} at 19:30 on ${date}`
        ];

        const listContainer = document.getElementById("flightList");
        listContainer.innerHTML = "";
        flights.forEach(flight => {
            const div = document.createElement("div");
            div.textContent = flight;
            div.onclick = () => selectFlight(transitName, flight);
            listContainer.appendChild(div);
        });

        document.getElementById("flightModalTitle").textContent = `Available Flights (${prev.value} → ${next.value})`;
        document.getElementById("flightModal").style.display = "block";
    }

    function closeFlightModal() {
        document.getElementById("flightModal").style.display = "none";
    }

    function selectFlight(name, flightInfo) {
        const item = itinerary.find(i => i.name === name);
        if (item) item.value = flightInfo;
        closeFlightModal();
        renderItinerary();
    }

    function openTransitOverlay(transitName) {
        const index = itinerary.findIndex(i => i.name === transitName);
        const from = findNearestLocation(index, -1);
        const to = findNearestLocation(index, 1);

        if (!from || !to || !from.value || !to.value) {
            alert("Missing valid nearby stops.");
            return;
        }

        // Fetch transit directions from your own backend to avoid CORS issues
        fetch('assets/php/api/get_Transit.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `fetchTransit=1&origin=${encodeURIComponent(from.value)}&destination=${encodeURIComponent(to.value)}`
        })
            .then(res => res.json())
            .then(data => {
                console.log(data)
                const steps = data?.directions_routes?.[0]?.legs?.[0]?.steps;
                if (!steps || steps.length === 0) {
                    alert("No transit directions found.");
                    return;
                }

                const directions = steps.map(step =>
                    `${step.travel_mode || ''}: ${step.instructions || step.html_instructions || ''}`
                ).join('<br>');

                showOverlayPopup(`Transit from <b>${from.value}</b> to <b>${to.value}</b><hr>${directions}`);
            })
            .catch(err => {
                console.error(err);
                alert("Failed to fetch transit information.");
            });
    }

    function showOverlayPopup(html) {
        document.getElementById("transitContent").innerHTML = html
        document.getElementById("transitOverlay").style.display = "block"
    }

    /*function showOverlayPopup(htmlContent) {
        const overlay = document.getElementById("mapModal");
        overlay.style.display = "block";
        document.getElementById("map").innerHTML = `<div style="padding: 10px;">${htmlContent}</div>`;
    }*/

    /*function findNearestLocation(index, direction) {
        let i = index + direction
        while (i >= 0 && i < itinerary.length) {
            if (["start", "stop"].includes(itinerary[i].type)) {
                return itinerary[i]
            }
            i += direction
        }
        return null
    }*/

    function closeDirectionsModal() {
        document.getElementById("directionsModal").style.display = "none"
    }

    function closeMapModal() {
        document.getElementById("mapModal").style.display = "none"
    }

    function searchHotelsAround(name) {
        const index = itinerary.findIndex(i => i.name === name)
        const nearby = findNearbyCoordinates(index)
        if (!nearby) {
            alert("No coordinates found nearby.")
            return
        }

        const {lat, lng} = nearby
        const radiusKm = 10

        fetch(`https://serpapi.com/search.json?engine=google_hotels&q=hotels&location=${lat},${lng}&api_key=${SERP_API_KEY}`)
            .then(res => res.json())
            .then(data => {
                showMapOverlay(name, data.hotels_results || [], lat, lng)
            })
            .catch(err => {
                console.error(err)
                alert("Failed to fetch hotels.")
            })
    }

    function findNearbyCoordinates(index) {
        const locationToCoords = {
            "Luxembourg": {lat: 49.6117, lng: 6.1319},
            "Barcelona, Catalonia, Spain": {lat: 41.3874, lng: 2.1686}
            // Add more hardcoded or fetched values here
        }

        // Try previous stop
        for (let i = index - 1; i >= 0; i--) {
            const val = itinerary[i].value
            if (val && locationToCoords[val]) return locationToCoords[val]
        }

        // Try next stop
        for (let i = index + 1; i < itinerary.length; i++) {
            const val = itinerary[i].value
            if (val && locationToCoords[val]) return locationToCoords[val]
        }

        return null
    }

    function showMapOverlay(name, hotels, lat, lng) {
        const mapModal = document.getElementById("mapModal")
        mapModal.style.display = "block"

        const suggestions = document.getElementById("searchSuggestions")
        suggestions.innerHTML = ""

        if (!window.hotelMap) {
            window.hotelMap = L.map('map').setView([lat, lng], 13)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(hotelMap)
        } else {
            hotelMap.setView([lat, lng], 13)
            hotelMarkers.forEach(marker => hotelMap.removeLayer(marker))
            hotelMarkers = []
        }

        hotels.forEach(hotel => {
            const hDiv = document.createElement("div")
            hDiv.textContent = hotel.name
            hDiv.onclick = () => {
                const item = itinerary.find(i => i.name === name)
                if (item) {
                    item.value = hotel.name
                    item.link = hotel.link
                    renderItinerary()
                    closeMapModal()
                }
            }
            suggestions.appendChild(hDiv)

            const marker = L.marker([hotel.latitude, hotel.longitude]).addTo(hotelMap)
                .bindPopup(hotel.name)
            marker.on('click', () => hDiv.click())
            hotelMarkers.push(marker)
        })
    }

    function updateSession() {
        $.ajax({
            url: "index.php",
            method: "POST",
            data: {
                Itinerary: JSON.stringify(itinerary)
            }
        })
    }

    document.getElementById("mapSearchInput").addEventListener("input", function () {
        const query = this.value;
        if (query.length < 3) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(results => {
                const suggestionBox = document.getElementById("searchSuggestions");
                suggestionBox.innerHTML = "";
                results.forEach(loc => {
                    const div = document.createElement("div");
                    div.textContent = loc.display_name;
                    div.onclick = () => selectLocation(loc);
                    suggestionBox.appendChild(div);
                });
            });
    });

    function selectLocation(loc) {
        if (!currentEditName) return;
        const item = itinerary.find(i => i.name === currentEditName);
        if (item) {
            item.value = loc.display_name;
            renderItinerary();
        }

        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([loc.lat, loc.lon]).addTo(map);
        map.setView([loc.lat, loc.lon], 13);

        document.getElementById("searchSuggestions").innerHTML = '';
        document.getElementById("mapModal").style.display = "none";
    }

    async function showCarTransit(transitName) {
        const index = itinerary.findIndex(i => i.name === transitName);
        const prev = itinerary[index - 1];
        const next = itinerary[index + 1];

        if (!prev || !next || !prev.value || !next.value) {
            alert("Start or end location is missing for routing.");
            return;
        }

        // Geocode using Nominatim
        const [startLoc, endLoc] = await Promise.all([
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(prev.value)}`).then(r => r.json()),
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(next.value)}`).then(r => r.json())
        ]);

        if (!startLoc.length || !endLoc.length) {
            alert("Could not find coordinates for one of the locations.");
            return;
        }

        const start = [parseFloat(startLoc[0].lat), parseFloat(startLoc[0].lon)];
        const end = [parseFloat(endLoc[0].lat), parseFloat(endLoc[0].lon)];

        // Fetch route from OSRM
        const url = `https://router.project-osrm.org/route/v1/driving/${start[1]},${start[0]};${end[1]},${end[0]}?overview=full&geometries=geojson`;
        const routeRes = await fetch(url);
        const routeData = await routeRes.json();

        if (!routeData.routes || !routeData.routes.length) {
            alert("Route could not be retrieved.");
            return;
        }

        // Show route on map
        openMap(transitName);
        setTimeout(() => {
            const coords = routeData.routes[0].geometry.coordinates.map(([lon, lat]) => [lat, lon]);

            if (window.routeLine) {
                map.removeLayer(window.routeLine);
            }

            window.routeLine = L.polyline(coords, { color: 'blue' }).addTo(map);
            map.fitBounds(window.routeLine.getBounds());
        }, 300);
    }

    renderItinerary();
    setInterval(updateSession, 15000);
</script>

<!--<script>
    const SERP_API_KEY = '86940468db8a3e69a0249a1cde207a1d556634e25398ec8ff99bad04a7939943' // Replace this

    let hotelMarkers = []

    let itinerary = [
        {
            name: "start",
            type: "start",
            category: "Location",
            value: "Luxembourg",
            startTime: "",
            endTime: "",
            before: null
        },
        {name: "transit1", type: "transit", category: "Flight", value: "", startTime: "", endTime: "", before: 1},
        {
            name: "end",
            type: "stop",
            category: "Location",
            value: "Barcelona, Catalonia, Spain",
            startTime: "",
            endTime: "",
            before: 0
        }
    ]


    // The rest of your JavaScript remains unchanged
    function isEditable() {
        const start = itinerary.find(i => i.name === "start" && i.value)
        const end = itinerary.find(i => i.name === "end" && i.value)
        return start && end
    }

    function addStopBefore(index) {
        const stopId = Date.now()
        const stop = {
            name: `stop${stopId}`,
            type: "stop",
            category: "Activity",
            value: "",
            startTime: "",
            endTime: "",
            before: null
        }
        const transit = {
            name: `transit${stopId}`,
            type: "transit",
            category: "Train",
            value: "",
            startTime: "",
            endTime: "",
            before: null
        }
        itinerary.splice(index, 0, transit, stop)
        rebuildBefore()
    }

    function removeItem(name) {
        const item = itinerary.find(i => i.name === name)
        if (item?.type === "transit") return
        const idx = itinerary.findIndex(i => i.name === name)
        const prevTransit = itinerary[idx - 1]
        if (prevTransit?.type === "transit") itinerary.splice(idx - 1, 2)
        else itinerary.splice(idx, 1)
        rebuildBefore()
    }

    function rebuildBefore() {
        const start = itinerary.find(i => i.type === "start")
        const end = itinerary.find(i => i.name === "end")
        const stops = itinerary.filter(i => i.type === "stop" && i.name !== "end")
        const transits = itinerary.filter(i => i.type === "transit")

        const ordered = [start]
        for (let i = 0; i < stops.length; i++) {
            const stop = stops[i]
            const transit = transits.find(t => itinerary.indexOf(t) < itinerary.indexOf(stop))
            if (transit) ordered.push(transit)
            ordered.push(stop)
        }
        const lastTransit = transits.find(t => itinerary.indexOf(t) < itinerary.indexOf(end))
        if (lastTransit) ordered.push(lastTransit)
        ordered.push(end)

        ordered.forEach((item, i) => {
            item.before = i === 0 ? null : i - 1
        })

        itinerary = ordered
        renderItinerary()
    }

    function renderItinerary() {
        const container = document.getElementById("timeline")
        container.innerHTML = ""

        const line = document.createElement("div")
        line.className = "center-line"
        container.appendChild(line)

        itinerary.forEach((item, index) => {
            if (item.type === "transit" && isEditable()) {
                const addRow = document.createElement("div")
                addRow.className = "add-section"
                const btn = document.createElement("button")
                btn.textContent = "+"
                btn.onclick = () => addStopBefore(index + 1)
                addRow.appendChild(btn)
                container.appendChild(addRow)
            }

            const row = document.createElement("div")
            row.className = "entry"
            row.dataset.name = item.name

            const left = document.createElement("div")
            left.className = "left"
            left.innerHTML = `
            <input type="datetime-local" value="${item.startTime}" onchange="updateTime('${item.name}', this.value, 'startTime')">
            <input type="datetime-local" value="${item.endTime || ''}" onchange="updateTime('${item.name}', this.value, 'endTime')">
        `

            const controls = document.createElement("div")
            controls.className = "controls"
            if (item.name !== "start" && item.name !== "end" && isEditable() && item.type !== "transit") {
                const removeBtn = document.createElement("button")
                removeBtn.textContent = "–"
                removeBtn.onclick = () => removeItem(item.name)
                controls.appendChild(removeBtn)
            }

            const right = document.createElement("div")
            right.className = "right"

            /*right.innerHTML = `
            <input class="location-search" placeholder="Click to pick location" readonly onclick="openMap('${item.name}')" value="${item.value}">
            ${item.type !== 'transit' ? `
            <select onchange="updateCategory('${item.name}', this.value)">
                <option ${item.category === 'Location' ? 'selected' : ''}>Location</option>
                <option ${item.category === 'Stay' ? 'selected' : ''}>Stay</option>
                <option ${item.category === 'Activity' ? 'selected' : ''}>Activity</option>
            </select>` : `
            <select onchange="updateCategory('${item.name}', this.value)">
                <option ${item.category === 'Flight' ? 'selected' : ''}>Flight</option>
                <option ${item.category === 'Train' ? 'selected' : ''}>Train</option>
                <option ${item.category === 'Bus' ? 'selected' : ''}>Bus</option>
                <option ${item.category === 'Taxi' ? 'selected' : ''}>Taxi</option>
            </select>`}
        `;*/

            let inputHTML = `<input class="location-search" placeholder="Click to pick..." readonly onclick="openHandler('${item.name}', '${item.type}', '${item.category}')" value="${item.value}">`

            let categoryOptions = ''
            if (item.type === 'transit') {
                categoryOptions = ['Flight', 'Train', 'Car',     'Bus', 'Taxi']
            } else {
                categoryOptions = ['Location', 'Stay', 'Activity']
            }
            right.innerHTML = inputHTML + `
        <select onchange="updateCategory('${item.name}', this.value)">
            ${categoryOptions.map(opt => `<option ${item.category === opt ? 'selected' : ''}>${opt}</option>`).join('')}
        </select>`


            right.appendChild(controls)
            row.appendChild(left)
            row.appendChild(right)
            container.appendChild(row)
        })
    }

    function updateTime(name, value, field) {
        const item = itinerary.find(i => i.name === name)
        if (item) item[field] = value
    }

    function updateCategory(name, value) {
        const item = itinerary.find(i => i.name === name)
        if (item) item.category = value
    }

</script>-->

<?php
echo(isset($_SESSION["Itinerary"]) ?? "No data");