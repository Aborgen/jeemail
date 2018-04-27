const getReceived = (id) => {
    const insecureTest = `http://api.jeemail.com/emails/received?id=${id}`;
    const secureTest = `https://api.jeemail.com/emails/received?id=${id}`;
    return fetch(secureTest, {
        method: 'POST',
        headers: {
               'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then((data) => data.json());
}

const getSent = (id) => {
    const insecureTest = `http://api.jeemail.com/emails/sent?id=${id}`;
    const secureTest = `https://api.jeemail.com/emails/sent?id=${id}`;
    return fetch(secureTest, {
        method: 'POST',
        headers: {
               'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then((data) => data.json());
}

export { getReceived, getSent };
