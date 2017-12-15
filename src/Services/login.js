const login = (user, pass) => {
    // This will prevent unwanted behavior when converting Unicode characters.
    let bluh = window.btoa((encodeURIComponent(`${user}:${pass}`)));
    return fetch('https://jeemail.api/login', {
        method: 'POST',
        headers: {
               'Content-Type': 'application/x-www-form-urlencoded',
               'Authorization': 'Basic ' + bluh
            }
        });
}

export default login;
