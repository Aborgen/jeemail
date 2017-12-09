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

/**
 * Will either set up a session or provide the client with an error.
 *
 * @param userPackage OBJECT
 *        {
 *          "username": STRING,
 *          "pass"    : STRING
 *        }
 */
const authorize = (userPackage) => {
    const {"username": user, "pass": pass} = userPackage;
    return fetch(`/public/getUser.php/api/authorize?user=${user}&pass=${pass}`,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
}

export { login, authorize };
