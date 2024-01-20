window.onload = () => {
    const fromDropdown = document.getElementById('from-dropdown');

    fromDropdown.addEventListener('change', getPossibleMoves);

    getPossibleMoves();
}

function getPossibleMoves() {
    const fromDropdown = document.getElementById('from-dropdown');
    const toDropdown = document.getElementById('to-dropdown');
    const submitButton = document.getElementById('move-submit-btn');

    var body = {
        from: fromDropdown.value
    };

    var encodedBody = Object.keys(body).map(function (key) {
        return encodeURIComponent(key) + '=' + encodeURIComponent(body[key]);
    }).join('&');


    fetch('endpoints/moveDropdown.php', {
        method: 'POST',
        body: encodedBody,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
        .then(response => response.json())
        .then(data => {
            toDropdown.innerHTML = '';
            if (data === null || data.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.innerHTML = '';
                submitButton.disabled = true;
                toDropdown.appendChild(option);
                return;
            }
            submitButton.disabled = false;

            data.forEach(pos => {
                const option = document.createElement('option');
                option.value = pos;
                option.innerHTML = pos;
                toDropdown.appendChild(option);
            });
        });
}