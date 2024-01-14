window.onload = () => {
    const fromDropdown = document.getElementById('from-dropdown');

    fromDropdown.addEventListener('change', getPossibleMoves);

    getPossibleMoves();
}

function getPossibleMoves() {
    const fromDropdown = document.getElementById('from-dropdown');
    const toDropdown = document.getElementById('to-dropdown');

    var body = {
        from: fromDropdown.value
    };

    console.log(body);

    var encodedBody = Object.keys(body).map(function (key) {
        return encodeURIComponent(key) + '=' + encodeURIComponent(body[key]);
    }).join('&');


    fetch('rules/getPossibleMoveCoordinates.php', {
        method: 'POST',
        body: encodedBody,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
        .then(response => response.json())
        .then(data => {
            toDropdown.innerHTML = '';
            data.forEach(pos => {
                const option = document.createElement('option');
                option.value = pos;
                option.innerHTML = pos;
                toDropdown.appendChild(option);
            });
        });
}