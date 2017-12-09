const getReceived = (id) => {
    const insecureTest = `http://jeemail.api/emails/received?id=${id}`;
    const secureTest = `https://jeemail.api/emails/received?id=${id}`;
    return fetch(secureTest, {
        method: 'POST',
        headers: {
               'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then((data) => data.json());
}

const getSent = (id) => {
    const insecureTest = `http://jeemail.api/emails/sent?id=${id}`;
    const secureTest = `https://jeemail.api/emails/sent?id=${id}`;
    return fetch(secureTest, {
        method: 'POST',
        headers: {
               'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then((data) => data.json());
}

export { getReceived, getSent };
