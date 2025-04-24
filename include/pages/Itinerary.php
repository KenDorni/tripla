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
    input[type="date"] {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
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
        padding-left: 75%;
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
</style>
</head>
<body>

<div class="line-wrapper">
    <div class="marker start-marker">S</div>
    <div class="vertical-line"></div>

    <div id="entriesContainer">
        <!-- One full block -->
        <div class="entry-wrapper">
            <div class="entry-row">
                <div class="date-side">
                    <button class="remove-btn">−</button>
                    <input type="date" name="date[]">
                </div>
                <div class="input-side">
                    <input type="text" name="input[]" placeholder="Type something...">
                </div>
            </div>
            <div class="add-btn-below"><button class="add-after add-btn">+</button></div>
        </div>
    </div>

    <div class="marker end-marker">E</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function createEntry() {
            return `
      <div class="entry-wrapper">
        <div class="entry-row">
          <div class="date-side">
            <button class="remove-btn">−</button>
            <input type="date" name="date[]">
          </div>
          <div class="input-side">




            <select name=''>
                <option value="function()"></option>
                <option value=""></option>
                <option value=""></option>
            </select>





            <input type="text" name="input[]" placeholder="Type something...">
          </div>
        </div>
        <div class="add-btn-below"><button class="add-after add-btn">+</button></div>
      </div>
    `;
        }

        // Add new entry below this one
        $('#entriesContainer').on('click', '.add-after', function () {
            $(this).closest('.entry-wrapper').after(createEntry());
        });

        // Remove entry
        $('#entriesContainer').on('click', '.remove-btn', function () {
            const wrapper = $(this).closest('.entry-wrapper');
            if ($('#entriesContainer .entry-wrapper').length > 1) {
                wrapper.remove();
            } else {
                alert("You need at least one input!");
            }
        });
    });
</script>