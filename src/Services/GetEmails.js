const getReceived = (id) => {
    try {
        let emails = fetch(`http://localhost:8080/public/getUser.php/api/emails/received/${id}`)
            .then((data) => {
                return data.json();
        });

        return emails;
    }
    catch (err) {
        console.log(err);
    }
}

const getSent = (id) => {
    try {
        let emails = fetch(`http://localhost:8080/public/getUser.php/api/emails/sent/${id}`)
            .then((data) => {
                return data.json();
        });

        return emails;
    }
    catch (err) {
        console.log(err);
    }
}

export { getReceived, getSent };
