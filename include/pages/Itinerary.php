<div class="container">
    <h1>Your Travel Itinerary</h1>
    <div id="timeline" class="timeline"></div>
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

<script>
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
    ];

    // The rest of your JavaScript remains unchanged
    function isEditable() {
        const start = itinerary.find(i => i.name === "start" && i.value);
        const end = itinerary.find(i => i.name === "end" && i.value);
        return start && end;
    }

    function addStopBefore(index) {
        const stopId = Date.now();
        const stop = {
            name: `stop${stopId}`,
            type: "stop",
            category: "Activity",
            value: "",
            startTime: "",
            endTime: "",
            before: null
        };
        const transit = {
            name: `transit${stopId}`,
            type: "transit",
            category: "Train",
            value: "",
            startTime: "",
            endTime: "",
            before: null
        };
        itinerary.splice(index, 0, transit, stop);
        rebuildBefore();
    }

    function removeItem(name) {
        const item = itinerary.find(i => i.name === name);
        if (item?.type === "transit") return;
        const idx = itinerary.findIndex(i => i.name === name);
        const prevTransit = itinerary[idx - 1];
        if (prevTransit?.type === "transit") itinerary.splice(idx - 1, 2);
        else itinerary.splice(idx, 1);
        rebuildBefore();
    }

    function rebuildBefore() {
        const start = itinerary.find(i => i.type === "start");
        const end = itinerary.find(i => i.name === "end");
        const stops = itinerary.filter(i => i.type === "stop" && i.name !== "end");
        const transits = itinerary.filter(i => i.type === "transit");

        const ordered = [start];
        for (let i = 0; i < stops.length; i++) {
            const stop = stops[i];
            const transit = transits.find(t => itinerary.indexOf(t) < itinerary.indexOf(stop));
            if (transit) ordered.push(transit);
            ordered.push(stop);
        }
        const lastTransit = transits.find(t => itinerary.indexOf(t) < itinerary.indexOf(end));
        if (lastTransit) ordered.push(lastTransit);
        ordered.push(end);

        ordered.forEach((item, i) => {
            item.before = i === 0 ? null : i - 1;
        });

        itinerary = ordered;
        renderItinerary();
    }

    function renderItinerary() {
        const container = document.getElementById("timeline");
        container.innerHTML = "";

        const line = document.createElement("div");
        line.className = "center-line";
        container.appendChild(line);

        itinerary.forEach((item, index) => {
            if (item.type === "transit" && isEditable()) {
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
            row.dataset.name = item.name;

            const left = document.createElement("div");
            left.className = "left";
            left.innerHTML = `
            <input type="datetime-local" value="${item.startTime}" onchange="updateTime('${item.name}', this.value, 'startTime')">
            <input type="datetime-local" value="${item.endTime || ''}" onchange="updateTime('${item.name}', this.value, 'endTime')">
        `;

            const controls = document.createElement("div");
            controls.className = "controls";
            if (item.name !== "start" && item.name !== "end" && isEditable() && item.type !== "transit") {
                const removeBtn = document.createElement("button");
                removeBtn.textContent = "–";
                removeBtn.onclick = () => removeItem(item.name);
                controls.appendChild(removeBtn);
            }

            const right = document.createElement("div");
            right.className = "right";

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

            let inputHTML = `<input class="location-search" placeholder="Click to pick..." readonly onclick="openHandler('${item.name}', '${item.type}', '${item.category}')" value="${item.value}">`;

            let categoryOptions = '';
            if (item.type === 'transit') {
                categoryOptions = ['Flight', 'Train', 'Bus', 'Taxi'];
            } else {
                categoryOptions = ['Location', 'Stay', 'Activity'];
            }
            right.innerHTML = inputHTML + `
        <select onchange="updateCategory('${item.name}', this.value)">
            ${categoryOptions.map(opt => `<option ${item.category === opt ? 'selected' : ''}>${opt}</option>`).join('')}
        </select>`;


            right.appendChild(controls);
            row.appendChild(left);
            row.appendChild(right);
            container.appendChild(row);
        });
    }

    function updateTime(name, value, field) {
        const item = itinerary.find(i => i.name === name);
        if (item) item[field] = value;
    }

    function updateCategory(name, value) {
        const item = itinerary.find(i => i.name === name);
        if (item) item.category = value;
    }

    function openHandler(name, type, category) {
        if (type === 'transit') {
            openTransitOverlay(name);
        } else if (category === 'Location') {
            openMap(name);
        } else if (category === 'Stay') {
            alert(`Search hotels for ${name} using SerpAPI`);
        } else if (category === 'Activity') {
            alert(`Search activities for ${name}`);
        }
    }


    let map;
    let marker;
    let selectedInputName = null;

    function openMap(name) {
        selectedInputName = name;
        const modal = document.getElementById("mapModal");
        modal.style.display = "block";

        if (!map) {
            map = L.map('map').setView([48.8566, 2.3522], 5); // Default center: Paris
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            map.on('click', function (e) {
                if (marker) marker.remove();
                marker = L.marker(e.latlng).addTo(map);
            });
        }

        document.getElementById("mapSearchInput").oninput = async function () {
            const query = this.value;
            const results = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`).then(res => res.json());
            const suggestions = document.getElementById("searchSuggestions");
            suggestions.innerHTML = "";

            results.slice(0, 5).forEach(loc => {
                const div = document.createElement("div");
                div.textContent = loc.display_name;
                div.onclick = () => {
                    if (marker) marker.remove();
                    const latlng = [parseFloat(loc.lat), parseFloat(loc.lon)];
                    map.setView(latlng, 13);
                    marker = L.marker(latlng).addTo(map);
                    const item = itinerary.find(i => i.name === selectedInputName);
                    if (item) item.value = loc.display_name;
                    closeMapModal();
                    renderItinerary();
                };
                suggestions.appendChild(div);
            });
        };
    }

    function openTransitOverlay(transitName) {
        const index = itinerary.findIndex(i => i.name === transitName);
        const from = findNearestLocation(index, -1);
        const to = findNearestLocation(index, 1);

        if (!from || !to) {
            alert("Missing nearby stops.");
            return;
        }

        const apiKey = "86940468db8a3e69a0249a1cde207a1d556634e25398ec8ff99bad04a7939943"; // Replace with your SerpAPI key
        const url = `https://serpapi.com/search.json?engine=google_maps_directions&origin=${encodeURIComponent(from.value)}&destination=${encodeURIComponent(to.value)}&mode=transit&api_key=${apiKey}`;

        fetch(url, {mode: 'no-cors'})
            .then(res => res.json())
            .then(data => {
                const steps = data?.directions_routes?.[0]?.legs?.[0]?.steps;
                if (!steps) {
                    alert("No route found.");
                    return;
                }

                const directions = steps.map(s => `${s.travel_mode}: ${s.instructions}`).join('<br>');
                showOverlayPopup(`Transit from <b>${from.value}</b> to <b>${to.value}</b><hr>${directions}`);
            })
            .catch(err => {
                console.error(err);
                alert("Failed to fetch directions.");
            });
    }

    function showOverlayPopup(html) {
        document.getElementById("transitContent").innerHTML = html;
        document.getElementById("transitOverlay").style.display = "block";
    }

    /*function findNearestLocation(index, direction) {
        let i = index + direction;
        while (i >= 0 && i < itinerary.length) {
            const item = itinerary[i];
            if (item.type === "start" || item.type === "stop") {
                return item;
            }
            i += direction;
        }
        return null;
    }*/

    function findNearestLocation(index, direction) {
        let i = index + direction;
        while (i >= 0 && i < itinerary.length) {
            if (["start", "stop"].includes(itinerary[i].type)) {
                return itinerary[i];
            }
            i += direction;
        }
        return null;
    }

    function closeDirectionsModal() {
        document.getElementById("directionsModal").style.display = "none";
    }

    function closeMapModal() {
        document.getElementById("mapModal").style.display = "none";
    }

    renderItinerary();
</script>