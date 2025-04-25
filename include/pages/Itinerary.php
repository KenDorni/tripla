<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vertical Timeline Inputs with API Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 40px;
        }

        .line-wrapper {
            position: relative;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .vertical-line {
            position: absolute;
            top: 30px;
            bottom: 30px;
            width: 2px;
            background-color: black;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
        }

        .marker {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            background-color: #000;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 2;
        }

        .start-marker {
            top: 0;
        }

        .end-marker {
            bottom: 0;
        }

        .entry-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .entry-row {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin: 20px 0 10px;
            position: relative;
            z-index: 1;
        }

        .date-side {
            width: 45%;
            text-align: right;
            padding-right: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 5px;
        }

        .input-side {
            width: 45%;
            text-align: left;
            padding-left: 20px;
        }

        input[type="text"],
        input[type="date"],
        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }

        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 16px;
            line-height: 25px;
            text-align: center;
            cursor: pointer;
        }

        .add-btn-below {
            display: flex;
            justify-content: flex-start;
            width: 90%;
            margin-top: 5px;
            margin-left: auto;
            padding-left: 75%; /* <- your preferred spacing */
        }

        .add-btn {
            background-color: #2ecc71;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 18px;
            color: white;
            cursor: pointer;
        }

        .api-popup {
            position: fixed;
            right: 20px;
            top: 100px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            padding: 15px;
            width: 250px;
            z-index: 999;
            display: none;
        }

        .api-popup h4 {
            margin-top: 0;
        }

        .api-popup ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .api-popup ul li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .api-popup ul li:hover {
            background: #f7f7f7;
        }
    </style>
</head>
<body>

<div class="line-wrapper">
    <div class="marker start-marker">S</div>
    <div class="vertical-line"></div>

    <div id="entriesContainer">
        <!-- Initial entry -->
        <div class="entry-wrapper">
            <div class="entry-row">
                <div class="date-side">
                    <button class="remove-btn">−</button>
                    <input type="date" name="date[]">
                    <input type="time" name="time[]">
                </div>
                <div class="input-side">
                    <div style="display: flex; gap: 10px;">
                        <select class="api-selector">
                            <option value="api1">API 1</option>
                            <option value="api2">API 2</option>
                            <option value="api3">API 3</option>
                        </select>
                        <input type="text" class="api-input" name="input[]" placeholder="Click or type to load API">
                    </div>
                </div>
            </div>
            <div class="add-btn-below"><button class="add-after add-btn">+</button></div>
        </div>
    </div>

    <div class="marker end-marker">E</div>
</div>

<!-- API Popup -->
<div class="api-popup" id="apiPopup">
    <h4>API Options</h4>
    <ul></ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const mockAPIs = {
        api1: ["Apple", "Axe", "Airplane"],
        api2: ["Beach", "Balls", "Baloon", "Bike"],
        api3: ["Cloud", "Camera", "Coffee"]
    };

    let currentInput = null;

    function showPopup(data, inputEl) {
        const popup = $('#apiPopup');
        const list = popup.find('ul');
        list.empty();
        data.forEach(item => {
            list.append(`<li>${item}</li>`);
        });
        popup.show();
        currentInput = inputEl;
    }

    function createEntry() {
        return `
      <div class="entry-wrapper">
        <div class="entry-row">
          <div class="date-side">
            <button class="remove-btn">−</button>
            <input type="date" name="date[]">
          </div>
          <div class="input-side">
            <div style="display: flex; gap: 10px;">
              <select class="api-selector">
                <option value="api1">API 1</option>
                <option value="api2">API 2</option>
                <option value="api3">API 3</option>
              </select>
              <input type="text" class="api-input" name="input[]" placeholder="Click or type to load API">
            </div>
          </div>
        </div>
        <div class="add-btn-below"><button class="add-after add-btn">+</button></div>
      </div>
    `;
    }

    function filterOptions(apiKey, keyword) {
        const options = mockAPIs[apiKey] || [];
        return options.filter(option => option.toLowerCase().startsWith(keyword.toLowerCase()));
    }

    $(document).ready(function () {
        const popup = $('#apiPopup');

        $('#entriesContainer').on('click', '.add-after', function () {
            $(this).closest('.entry-wrapper').after(createEntry());
        });

        $('#entriesContainer').on('click', '.remove-btn', function () {
            const wrapper = $(this).closest('.entry-wrapper');
            if ($('#entriesContainer .entry-wrapper').length > 1) {
                wrapper.remove();
            } else {
                alert("You need at least one input!");
            }
        });

        $('#entriesContainer').on('click', '.api-input', function () {
            const row = $(this).closest('.entry-row');
            const api = row.find('.api-selector').val();
            const data = mockAPIs[api] || [];
            showPopup(data, $(this));
        });

        // Dynamic search
        $('#entriesContainer').on('input', '.api-input', function () {
            const row = $(this).closest('.entry-row');
            const api = row.find('.api-selector').val();
            const value = $(this).val();
            const filtered = filterOptions(api, value);
            showPopup(filtered, $(this));
        });

        // Click popup item -> insert into input
        $('#apiPopup').on('click', 'li', function () {
            const value = $(this).text();
            if (currentInput) {
                currentInput.val(value);
            }
            $('#apiPopup').hide();
        });

        // Hide popup if clicked outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.api-popup, .api-input, .api-selector').length) {
                $('#apiPopup').hide();
            }
        });
    });
</script>

</body>
</html>
