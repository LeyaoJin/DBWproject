document.addEventListener("DOMContentLoaded", function () {
    var map = L.map("map").setView([20, 0], 2);

    // Tile Layer (CartoDB Positron)
    L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png",
        {
            attribution:
                '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | Tiles by <a href="https://carto.com/">Carto</a>',
            subdomains: "abcd",
        }
    ).addTo(map);

    var countryToCodeMap = {}; // Map from mortality country names -> ISO3 Code
    var countryData = {}; // Map from Country Code -> Death Count

    /** 
     * Load and process the country mappings 
     */
    function loadCountryMappings() {
        return new Promise((resolve, reject) => {
            Papa.parse("/country_data.csv", {
                download: true,
                header: true,
                dynamicTyping: true,
                complete: function (results) {
                    console.log("CSV Data Loaded:", results.data);

                    results.data.forEach(function (row) {
                        if (row && row["mortality_country_names"] && row["Country Code"]) {
                            let mortalityName = String(row["mortality_country_names"]).trim(); // 4th column
                            let countryCode = String(row["Country Code"]).trim(); // 2nd column (ISO3)
                            countryToCodeMap[mortalityName] = countryCode;
                        }
                    });

                    console.log("Generated Country Code Mapping:", countryToCodeMap);
                    resolve();
                },
                error: function (error) {
                    console.error("Error loading country mappings:", error);
                    reject(error);
                },
            });
        });
    }

    /**
     * Fetch mortality data and map it correctly
     */
    function loadMortalityData() {
        return fetch("/fetch_data.php?disease=HIV")
            .then((response) => response.json())
            .then((data) => {
                console.log("Fetched Data:", data);

                if (!data.mortality_data || !Array.isArray(data.mortality_data)) {
                    console.error("Error: mortality_data is missing or not an array", data);
                    return;
                }

                // Process each mortality entry
                data.mortality_data.forEach((entry) => {
                    let countryCode = countryToCodeMap[entry.country]; // Convert country name to ISO3 code
                    if (countryCode) {
                        if (!countryData[countryCode]) {
                            countryData[countryCode] = 0;
                        }
                        countryData[countryCode] += entry.deaths_count; // Sum deaths per country
                    } else {
                        console.warn(`No country code found for: ${entry.country}`);
                    }
                });

                console.log("Processed Mortality Data:", countryData);
                loadGeoJSON(); // Load the GeoJSON map once data is ready
            })
            .catch((error) => console.error("Error fetching mortality data:", error));
    }
    function getColor(deaths) {
        if (deaths > 500000) return "#2D003D"; // Deepest Purple for extreme cases
        if (deaths > 100000) return "#4B0082"; // Dark Indigo for very high deaths
        if (deaths > 50000) return "#6A0DAD"; // Dark Purple
        if (deaths > 10000) return "#8000FF"; // Bright Purple
        if (deaths > 5000) return "#9932CC"; // Amethyst
        if (deaths > 1000) return "#BA55D3"; // Medium Orchid
        if (deaths > 500) return "#DA70D6"; // Orchid
        if (deaths > 100) return "#EE82EE"; // Violet
        if (deaths > 50) return "#FFB6C1"; // Light Pinkish Purple
        if (deaths > 10) return "#FFC0CB"; // Pink
        if (deaths > 2) return "#FFE6F7"; // Very Light Pink
        if (deaths > 0) return "#FAF0FF"; // Almost White Purple
        return "#D3D3D3"; // Neutral Light Gray for No Data
    }
    
    // Style function to apply colors
    function style(feature) {
        let countryCode = feature.properties.sr_adm0_a3; // Get country code from GeoJSON
        let deaths = countryData[countryCode] || null; // Find deaths count by country code
    
        return {
            fillColor: deaths === null ? "#D3D3D3" : getColor(deaths), // Gray for no data
            weight: 1,
            opacity: 1,
            color: "black",
            dashArray: "3",
            fillOpacity: 0.7
        };
    }
    
    // Add a Legend
    function addLegend(map) {
        let legend = L.control({ position: "bottomright" });
    
        legend.onAdd = function () {
            let div = L.DomUtil.create("div", "info legend");
            let grades = [0, 2, 10, 50, 100, 500, 1000, 5000, 10000, 50000, 100000, 500000];
            let labels = [];
            let from, to;
    
            div.innerHTML += "<strong>Deaths Count</strong><br>";
    
            for (let i = 0; i < grades.length; i++) {
                from = grades[i];
                to = grades[i + 1];
    
                labels.push(
                    '<i style="background:' +
                        getColor(from + 1) +
                        '; width: 20px; height: 15px; display: inline-block;"></i> ' +
                        from +
                        (to ? " – " + to : "+")
                );
            }
    
            div.innerHTML += labels.join("<br>");
            return div;
        };
    
        legend.addTo(map);
    }
    
    // Add ocean background color
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".leaflet-container").style.backgroundColor = "#B3E5FC"; // Baby Blue
    });
    

    /**
     * Load and display the GeoJSON map
     */
    function loadGeoJSON() {
        fetch("/geojson.json")
            .then((response) => response.json())
            .then((geojson) => {
                L.geoJSON(geojson, {
                    style: style,
                    onEachFeature: function (feature, layer) {
                        let countryCode = feature.properties.sr_adm0_a3;
                        let deaths = countryData[countryCode] || "No Data";

                        layer.bindTooltip(feature.properties.sr_subunit + ": " + deaths, {
                            permanent: false,
                            direction: "top",
                        });
                    },
                }).addTo(map);
            })
            .catch((err) => console.error("Error loading GeoJSON:", err));
    }

    /**
     * Initialize the entire map process
     */
    async function initializeMap() {
        await loadCountryMappings();
        await loadMortalityData();
    }

    initializeMap();
});
