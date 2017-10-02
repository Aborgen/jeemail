const getEmails = async (start=0, limit=100) => {
    try {
        let emails = await (await fetch(`http://jsonplaceholder.typicode.com/posts?_start=${start}&_limit=${limit}`)).json();
        // console.log(emails);
        return emails;
    }
    catch (err) {
        console.log(err);
    }
}

export default getEmails;
